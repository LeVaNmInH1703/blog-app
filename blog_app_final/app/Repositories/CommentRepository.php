<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Emoji;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class CommentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Comment::class);
    }
    public function getCommentsBase()
    {
        return Comment::with([
            'user:id,name,avatar_filename',
            'emojis.user:id,name,avatar_filename',
            'myEmoji',
            'medias' => function ($query) {
                $query->limit(3);
            },
            'blog',
        ])
            ->withCount(['emojis', 'replies', 'medias']);
    }
    public function getRootCommentsByBlogId($blogId)
    {
        // Lấy danh sách comment gốc với thông tin cần thiết
        $rootComments = $this->getCommentsBase()->where('blog_id', $blogId)
            ->whereDoesntHave('parent')
            ->orderBy('created_at', 'desc')
            ->get();
        return $rootComments;
    }
    public function getReplies($commentId)
    {
        return $this->getCommentsBase()->where('parent_id', $commentId)->orderBy('created_at', 'desc')
            ->get();
    }
    public function countRootComments($blogId)
    {
        return Comment::where('blog_id', $blogId)
            ->whereDoesntHave('parent')->count();
    }
    public function getCommentById($id)
    {
        $comment = $this->getCommentsBase()->where('id', $id)
            ->first();
        return $comment;
    }

    public function countCommentsByBlogId($blogId)
    {
        return Comment::where('blog_id', $blogId)->count();
    }
    public function getWithEmojis($id)
    {
        return Comment::with(['emojis' => function ($query) {
            $query->orderBy('created_at', 'desc')  // Sắp xếp theo thời gian tạo emoji (hoặc theo số lần sử dụng)
                ->limit(3);
        }])->where('id', $id)->first();
    }
    public function getEmojisCount($id)
    {
        return Comment::withCount(['emojis'])
            ->where('id', $id)->first()->emojis_count;
    }
    public function getCommentsCount($id)
    {
        return Comment::withCount(['replies'])
            ->where('id', $id)->first()->replies_count;
    }
    public function getMyEmoji($id)
    {
        return Comment::find($id)->myEmoji;
    }
    public function create($request, $blog, $comment = null)
    {
        return Comment::create([
            'user_id' => Auth::id(),
            'blog_id' => $blog->id,
            'content' => $request->input('content'),
            'parent_id' => $comment ? $comment->id : null
        ]);
    }
}
