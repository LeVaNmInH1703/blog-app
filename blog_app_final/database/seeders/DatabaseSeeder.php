<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\Emoji;
use App\Models\EmojiBlogDetail;
use App\Models\EmojiCommentDetail;
use App\Models\Friendships;
use App\Models\Message;
use App\Models\ReplyCommentDetail;
use App\Models\User;
use Database\Factories\ReplyCommentDetailFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Nette\Utils\Random;

class DatabaseSeeder extends Seeder
{
    public $emojis = ['like', 'love', 'wow', 'sad', 'haha', 'care', 'angry'];
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createUser();
        $this->friendShip();
        $this->createBlog();
        $this->createEmojiBlog();
        $this->createComments();
        $this->createEmojiComment();
        $this->createChat();
        $this->createMessage();
    }
    public function createMessage()
    {
        for ($i = 1; $i <= 1000; $i++) {
            // Lấy chat ngẫu nhiên
            $chat = Chat::inRandomOrder()->first();

            // Lấy người dùng ngẫu nhiên trong chat (user đã tham gia vào chat)
            $user = $chat->users()->inRandomOrder()->first();  // Đảm bảo rằng bảng pivot 'chat_user' tồn tại và có quan hệ đúng

            // Tạo nội dung tin nhắn ngẫu nhiên
            $content = fake()->paragraph(rand(1, 5));  // Nội dung tin nhắn có độ dài ngẫu nhiên từ 1 đến 5 đoạn văn

            // Tạo tin nhắn mới
            Message::create([
                'user_id' => $user->id,  // ID người gửi tin nhắn
                'chat_id' => $chat->id,  // ID của chat nơi tin nhắn được gửi
                'content' => $content,   // Nội dung tin nhắn
            ]);
        }
    }

    public function createChat()
    {
        for ($i = 1; $i < 10; $i++) {
            // Chọn ngẫu nhiên loại chat
            $chatType = Arr::random(['public', 'private']);

            if ($chatType == 'public') {
                // Lấy admin ngẫu nhiên
                $admin = User::inRandomOrder()->first();

                // Lấy một nhóm người dùng ngẫu nhiên, không trùng với admin
                $othersUser = User::inRandomOrder()
                    ->where('id', '!=', $admin->id) // Loại bỏ admin
                    ->limit(rand(2, 7)) // Chọn 5 người tham gia chat
                    ->get();

                // Tạo tên chat là danh sách các id người tham gia
                $name = $admin->id . ' ' . $othersUser->pluck('id')->implode(' '); // Tạo tên chat bằng cách nối id của admin và các user tham gia

                // Tạo chat kiểu public
                $chat = Chat::create([
                    'chat_type' => $chatType,
                    'name' => $name,
                    'avatar_filename' => 'groupDefault.png',
                ]);

                // Gắn người tham gia vào chat (admin và những người khác)
                $chat->users()->attach($admin->id, ['role' => 'admin']); // Thêm admin vào chat
                foreach ($othersUser as $user) {
                    $chat->users()->attach($user->id, ['role' => 'member']); // Thêm các user vào chat
                }
            } else if ($chatType == 'private') {
                // Lấy admin ngẫu nhiên
                $admin = User::inRandomOrder()->first();

                // Lấy một người bạn ngẫu nhiên của admin
                $admin2 = $admin->friends()->inRandomOrder()->first();
                if (!$admin2) continue;
                // Tạo tên chat dựa trên phép toán với các id của admin và admin2
                $name = ($admin->id + $admin2->id) . "" . ($admin->id * $admin2->id);

                // Tạo chat kiểu private
                $chat = Chat::create([
                    'chat_type' => $chatType,
                    'name' => $name,
                    'avatar_filename' => 'groupDefault.png',
                ]);

                // Thêm admin và admin2 vào chat
                $chat->users()->attach([$admin->id, $admin2->id], ['role' => 'admin']);
            }
        }
    }

    public function createUser()
    {
        for ($i = 1; $i < 10; $i++) {
            $email = $i . '@gmail.com';
            $avatar_filename = 'avatar' . ($i % 7 + 1) . '.png';
            User::factory()->create([
                'email' => $email,
                'name' => $email,
                'password' => $email,
                'avatar_filename' => $avatar_filename
            ]);
        }
    }
    public function friendShip()
    {
        for ($i = 1; $i < 30; $i++) {
            Friendships::factory()->create();
            //lưu ý ->count() sẽ k phải create one mà là create all nên sẽ không thể lấy giá trị bản ghi trước
        }
    }
    public function createBlog()
    {
        Blog::factory()->count(30)->create();
    }
    public function createEmojiBlog()
    {
        for ($i = 1; $i <= 60; $i++) {
            $blog = Blog::inRandomOrder()->first();
            $user = User::inRandomOrder()->first();
            $emoji = Arr::random($this->emojis);

            // Kiểm tra nếu emoji đã tồn tại
            $existingEmoji = $blog->emojis()->where('user_id', $user->id)->first();

            if (!$existingEmoji) {
                // Nếu chưa tồn tại, tạo mới
                $blog->emojis()->create([
                    'name' => $emoji,
                    'user_id' => $user->id,
                ]);
            }
        }
    }

    public function createComments()
    {
        for ($i = 1; $i <= 200; $i++) {
            // Lấy một blog ngẫu nhiên
            $blog = Blog::inRandomOrder()->first();

            // Kiểm tra nếu comment này có parent (con)
            $parentId = $this->getValidParentId($blog->id); // Truyền blog_id vào để đảm bảo parent_id hợp lệ

            // Tạo bình luận mới
            Comment::factory()->create([
                'user_id' => User::inRandomOrder()->first()->id,
                'blog_id' => $blog->id,  // Đảm bảo comment thuộc về blog này
                'content' => fake()->paragraph(1),
                'parent_id' => $parentId  // Đảm bảo parent comment thuộc cùng blog
            ]);
        }
    }

    // Phương thức để lấy parent_id hợp lệ (có cùng blog_id)
    public function getValidParentId($blogId)
    {
        // Lọc các comment có cùng blog_id, sau đó chọn ngẫu nhiên 1 comment làm parent
        $parentComment = Comment::where('blog_id', $blogId)->inRandomOrder()->first();

        // Nếu có parent, trả về ID của nó, nếu không thì trả về null
        return $parentComment ? $parentComment->id : null;
    }

    public function createEmojiComment()
    {
        for ($i = 1; $i <= 60; $i++) {
            $comment = Comment::inRandomOrder()->first();
            $user = User::inRandomOrder()->first();
            $emoji = Arr::random($this->emojis);
            $existingEmoji = $comment->emojis()->where('user_id', $user->id)->first();
            if (!$existingEmoji)
                $comment->emojis()->create([
                    'name' => $emoji,
                    'user_id' => $user->id,
                ]);
        }
    }
}
