<?php

namespace App\Http\Controllers;

use App\Events\BlogEvent;
use App\Events\HasNewNotificationEvent;
use App\Events\PostEvent;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Emoji;
use App\Models\EmojiBlogDetail;
use App\Models\EmojiCommentDetail;
use App\Models\FileBlog;
use App\Models\Notification;
use App\Models\User;
use App\Services\BlogService;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BlogController extends Controller
{
    protected $blogService;
    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }
    public function index()
    {
        if (!Auth::user()->email_verified_at)
            return redirect()->route('waitingForEmailVerification');
        $blogs = $this->blogService->getBlogs();

        return view("pages.home", compact("blogs"));
    }
    public function createBlogGetView()
    {
        return view('pages.createPost');
    }




    public function loadMoreBlog(Request $request)
    {
        $blogs = $this->blogService->getBlogs();
        $innerHTML = '';
        foreach ($blogs as $blog)
            $innerHTML .= view('components/blog-component', compact('blog'))->render();
        return response()->json([
            'innerHTML' => $innerHTML,
            'nextPageUrl' => $blogs->nextPageUrl(),
        ]);
    }



    // public function takeAndSetSomeBlog($n = 5, $loadedBlogs = [])
    // {
    //     // dd(Blog->temp());
    //     $blogs = Blog::with(['files', 'emojis', 'user', 'comments'])
    //         ->whereNotIn('id', $loadedBlogs)
    //         ->inRandomOrder()
    //         // ->skip($offset)
    //         ->take($n)
    //         ->get();
    //     dd($blogs,now());
    //     // dd(Blog::with('images','comments','emojis')->count(),Blog::all()->count());
    //     foreach ($blogs as $blog)
    //         // $this->setBlog($blog);
    //     return $blogs;
    // }
    // public function setBlog(Blog &$blog)
    // {
    //     $blog->countEmoji = $blog->emojis->count();
    //     $blog->countComment = $blog->comments->count();
    //     //cảm xúc của người dùng với blog này
    //     $blog->clientEmoji = EmojiBlogDetail::where([['user_id', Auth::id()], ['blog_id', $blog->id]])->first();
    //     // dd($blog->clientEmoji);
    //     if ($blog->clientEmoji)
    //         $blog->clientEmoji = $blog->clientEmoji->emoji;
    // }



    public function blogDetail($blog)
    {
        $blog = $this->blogService->getBlogDetail($blog);
        if (!$blog) abort(404);
        // dd($blog);
        return view('pages.blogDetail', compact('blog'));
    }



    // public function setComment(Comment &$comment, $level = 0)
    // {
    //     $comment->countEmoji = $comment->emojis->count();
    //     $comment->clientEmoji = EmojiCommentDetail::where([['user_id', Auth::id()], ['comment_id', $comment->id]])->first();
    //     if ($comment->clientEmoji)
    //         $comment->clientEmoji = $comment->clientEmoji->emoji;
    //     // Log::info($comment->id.' '.$level);
    //     foreach ($comment->comments as $commentChild) {
    //         $this->setComment($commentChild);
    //     }
    // }
    // public function findObjByName($name, $objID)
    // {
    //     if ($name == 'blog') {
    //         return Blog::with(['files', 'comments.comments', 'emojis'])->find($objID);
    //     } else if ($name == 'comment') {
    //         return Comment::with(['comments', 'emojis'])->find($objID);
    //     }
    // }
    // public function toggleEmoji($name, $objID)
    // {
    //     $obj = $this->findObjByName($name, $objID);
    //     if (!$obj || ($name != 'blog' && $name != 'comment'))
    //         abort(404);
    //     $getEmojiRender = '';
    //     if ($this->takeEmoji($name, $obj)->exists()) {
    //         $this->useTransaction(function () use ($name, $obj) {
    //             $this->takeEmoji($name, $obj)->delete();
    //         });
    //         $getEmojiRender = $this->getEmoji();
    //     } else {
    //         $emoji = Emoji::where('name', 'heart')->first();
    //         $this->createEmoji($name, $obj, $emoji);
    //         $getEmojiRender = $this->getEmoji($emoji);
    //     }
    //     return response()->json([
    //         'countEmojiRender' => str($this->countEmoji($name, $objID)),
    //         'getEmojiRender' => str($getEmojiRender),
    //         'test' => 'aaaaaaaaaaaaaa'
    //     ]);
    // }
    // public function countEmoji($name, $objID)
    // {
    //     $obj = $this->findObjByName($name, $objID);
    //     if (!$obj || ($name != 'blog' && $name != 'comment')) abort(404);
    //     if ($name == 'blog') {
    //         $obj->clientEmoji = EmojiBlogDetail::where([['user_id', Auth::id()], ['blog_id', $obj->id]])->first();
    //         $size = 18;
    //     } else if ($name == 'comment') {
    //         $obj->clientEmoji = EmojiCommentDetail::where([['user_id', Auth::id()], ['comment_id', $obj->id]])->first();
    //         $size = 12;
    //     }
    //     $obj->countEmoji = $obj->emojis->count();
    //     return (new CountEmojiComponent($obj, $size))->render();
    // }
    // public function getEmoji(Emoji $emoji = null, $isshowName = true, $size = 18)
    // {
    //     if (($isshowName != true && $isshowName != false)) abort(404);
    //     return (new GetEmojiComponent($emoji, $isshowName, $size))->render();
    // }
    // public function createEmoji($name, $objID, Emoji $emoji)
    // {
    //     $obj = $this->findObjByName($name, $objID);
    //     if (!$obj || ($name != 'blog' && $name != 'comment') || !$emoji) {
    //         //blog k có hoặc người dùng bị chặn
    //         abort(404);
    //     }

    //     if ($this->takeEmoji($name, $obj)->exists()) {
    //         //update emoji
    //         $this->updateEmoji($name, $obj, $emoji);
    //     } else {
    //         $this->createEmoji($name, $obj, $emoji);
    //         //create new emoji
    //     }
    //     return response()->json([
    //         'countEmojiRender' => str($this->countEmoji($name, $objID)),
    //         'getEmojiRender' => str($this->getEmoji($emoji)),
    //     ]);
    // }
    // public function deleteEmoji($name, $objID)
    // {
    //     $obj = $this->findObjByName($name, $objID);
    //     if (!$obj || ($name != 'blog' && $name != 'comment')) {
    //         abort(404);
    //     }
    //     $this->useTransaction(function () use ($name, $obj) {
    //         $this->takeEmoji($name, $obj)->first()->delete();
    //     });
    //     return redirect()->back();
    // }
    // public function updateEmoji($name, $obj, Emoji $emoji)
    // {
    //     return $this->useTransaction(function () use ($name, $obj, $emoji) {
    //         $this->takeEmoji($name, $obj)->first()->update([
    //             'emoji_id' => $emoji->id
    //         ]);
    //     });
    // }
    // public function createEmoji($name, $obj, Emoji $emoji)
    // {
    //     $this->createNotify($name, $obj, $emoji);
    //     return $this->useTransaction(function () use ($name, $obj, $emoji) {
    //         if ($name == 'blog') {
    //             EmojiBlogDetail::create([
    //                 'user_id' => Auth::id(),
    //                 'blog_id' => $obj->id,
    //                 'emoji_id' => $emoji->id
    //             ]);
    //         } else if ($name == 'comment') {
    //             EmojiCommentDetail::create([
    //                 'user_id' => Auth::id(),
    //                 'comment_id' => $obj->id,
    //                 'emoji_id' => $emoji->id
    //             ]);
    //         }
    //     });
    // }
    // public function createNotify($name, $obj, $emoji)
    // {
    //     if($obj->user->id==Auth::id()) return;
    //     if ($obj->emojis->count() <= 1)
    //         $content = __("public.Someone reacted to your something", ['Someone' => Auth::user()->name, 'something' => $name]);
    //     else
    //         $content = __("public.Someone and some others reacted to your something", ['Someone' => Auth::user()->name, 'some' => $obj->emojis->count() - 1, 'something' => $name]);
    //     $content = $content . " " . $this->getEmoji($emoji, false, 15);
    //     $notification = Notification::create([
    //         'user_id' => Auth::id(),
    //         'content' => $content,
    //         'user_id_receive' => $obj->user->id,
    //         'link' => route('blogDetail', ($name == 'comment' ? $obj->blog->id : $obj->id)),
    //         'key_word'=>$name.'-'.$obj->id
    //     ]);
    //     event(new HasNewNotificationEvent($notification));
    // }
    // public function takeEmoji($name, $obj)
    // {
    //     if ($name == 'blog') {
    //         return EmojiBlogDetail::where([['blog_id', $obj->id], ['user_id', Auth::id()]]);
    //     } else if ($name == 'comment') {
    //         return EmojiCommentDetail::where([['comment_id', $obj->id], ['user_id', Auth::id()]]);
    //     }
    // }
    public function createBlog(Request $request)
    {
        $content=$request->input('content');
        if ($content == "" && $request->files == "")
            return redirect()->back()->with(['message' => __('public.Please write something !')]);
        // $request->validate([
        //     'files.*' => 'file',
        // ]);
        $newBlog = $this->blogService->createBlog($request);
        // $result = $this->useTransaction(function () use ($request, &$newBlog) {
        //     // $newBlog = Blog::create([
        //     //     'content' => $request->input('content'),
        //     //     'user_id' => Auth::id(),
        //     // ]);
        //     $files = $request->file('files');
        //     if ($files) {
        //         foreach ($files as $file) {
        //             $mimeType = $file->getMimeType();
        //             if ($file) {

        //                 if (str_starts_with($mimeType, 'image/')) {
        //                     $fileBlogName = 'blog_image_' . $newBlog->id . Str::random(20) . '.' . $file->extension();

        //                     // resize chú ý dùng đúng driver
        //                     $manager = new ImageManager(new Driver());
        //                     // $manager->read($image)->resize(250, 250)->save(public_path('images_resize') .'/'. $fileBlogName);
        //                     $temp = $manager->read($file);
        //                     if ($temp->width() > 640)
        //                         $temp->resize(640, 640 * $temp->height() / $temp->width());

        //                     $temp->save(public_path('images_resize') . '/' . $fileBlogName);

        //                     //move
        //                     $file->move(public_path('images'), $fileBlogName);
        //                     FileBlog::create([
        //                         'file_name' => $fileBlogName,
        //                         'blog_id' => $newBlog->id
        //                     ]);
        //                 } else if (str_starts_with($mimeType, 'video/')) {
        //                     $fileBlogName = 'blog_video_' . $newBlog->id . Str::random(20) . '.' . $file->extension();
        //                     //move
        //                     $file->move(public_path('videos'), $fileBlogName);

        //                     FileBlog::create([
        //                         'file_name' => $fileBlogName,
        //                         'blog_id' => $newBlog->id
        //                     ]);
        //                 }
        //             }
        //         }
        //     }
        // });
        if ($newBlog->id) {
            return redirect()->route('home')->with('message', __('public.Posted'));
        } else {
            return redirect()->back()->with('message', "Error");
        }
    }
}
