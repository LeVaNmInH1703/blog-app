<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\Emoji;
use Illuminate\Support\Facades\Auth;

class BlogRepository extends BaseRepository
{
    public $commentRepository;
    public $emojiBlogDetailRepository;
    public function __construct()
    {
        parent::__construct(Blog::class);
    }
    public function baseQueryGetBlog()
    {
        return Blog::with([
            'user:id,name,avatar_filename',
            'medias' => function ($query) {
                $query->limit(5);
            },
            'emojis' => function ($query) {
                $query->orderBy('created_at', 'desc')  // Hoặc theo số lần sử dụng emoji
                    ->limit(3);
            },
            'emojis.user:id,name,avatar_filename',
            'myEmoji'
        ])
            ->withCount([
                'comments',      // Đếm số comment
                'emojis',  // Đếm số emoji
                'medias',
            ]);
    }
    public function getBlogsWithDetails($limit = 5)
    {
        $blogs = $this->baseQueryGetBlog()
            ->latest()
            ->paginate($limit, ['*'], 'blogPage');
        return $blogs;
    }
    public function getBlogWithDetails($id)
    {
        $blog = $this->baseQueryGetBlog()->where('id', $id)->first();
        return $blog;
    }
    public function getWithEmojis($id)
    {
        return Blog::with(['emojis' => function ($query) {
            $query->orderBy('created_at', 'desc')  // Sắp xếp theo thời gian tạo emoji (hoặc theo số lần sử dụng)
                ->limit(3);
        }])->where('id', $id)->first();
    }
    public function getCommentsCount($id)
    {
        return Blog::withCount(['comments'])
            ->where('id', $id)->first()->comments_count;
    }
    public function getEmojisCount($id)
    {
        return Blog::withCount(['emojis'])
            ->where('id', $id)->first()->emojis_count;
    }
    public function getMyEmoji($id)
    {
        return Blog::find($id)->myEmoji;
    }
    public function createBlog($content){
        return Blog::create([
            'content' => $content,
            'user_id' => Auth::id(),
        ]);
    }
}
