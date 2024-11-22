<?php

namespace App\Http\Controllers;

use App\Events\HasNewNotificationEvent;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\ImageComment;
use App\Models\Notification;
use App\Models\ReplyCommentDetail;
use App\Services\BlogService;
use App\Services\CommentService;
use App\View\Components\CommentComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CommentController extends Controller
{
    public $commentService, $blogService;
    public function __construct(CommentService $commentService, BlogService $blogService)
    {
        $this->commentService = $commentService;
        $this->blogService = $blogService;
    }

    // public function saveCommentImage(Request $request, Comment $newComment)
    // {
    //     $image = $request->file('fileImage');
    //     if ($image) {
    //         $imageAvatarName = 'comment_image_' . $newComment->id . Str::random(20) . '.' . $image->extension();

    //         // resize chú ý dùng đúng driver
    //         $manager = new ImageManager(new Driver());
    //         $manager->read($image)->resize(250, 250)->save(public_path('images_resize') . '/' . $imageAvatarName);

    //         //move
    //         $image->move(public_path('images'), $imageAvatarName);
    //         ImageComment::create([
    //             'image_comment_name' => $imageAvatarName,
    //             'comment_id' => $newComment->id
    //         ]);
    //     }
    // }
    public function createComment(Request $request, Blog $blog, Comment $parent = null)
    {
        if (!$blog)
            abort(404);
        
        $newComment = $this->commentService->createComment($request, $blog, $parent);
        $newComment = $this->commentService->setComment($newComment->id);
        $result = [
            'newComment' => $newComment,
            'blogCountCommentInnerText' => $this->blogService->getCommentsCount($newComment->blog->id),
            'newCommentInnerHTML' => view('components.comment-component', ['comment' => $newComment])->render(),
        ];
        // nếu có comment parent
        if ($parent) {
            $result = array_merge($result, [
                'parentCountCommentInnerHTML' => view(
                    'components.comment-count-comment-component',
                    ['number' => $this->commentService->getCommentsCount($newComment->parent_id)]
                )->render(),
            ]);
        }
        // $this->createNotify($newComment,($comment?$comment->user->id:$blog->user->id), __((!$comment ? "public.Someone commented on your blog: content" : 'public.Someone replied to your comment: content'), ['Someone' => Auth::user()->name, 'content' => $newComment->content]));
        return response()->json($result);
    }
    public function getReplies($commentId)
    {
        if (!$commentId)
            abort(404);
        $replies = $this->commentService->getReplies($commentId);
        $commentChildrenInnerHTML = '';
        foreach ($replies as $reply) {
            $commentChildrenInnerHTML .= view('components.comment-component', ['comment' => $reply])->render();
        }
        return response()->json(['replies' => $replies, 'commentChildrenInnerHTML' => $commentChildrenInnerHTML]);
    }
    // public function createNotify($newComment,$user_id_receive, $content)
    // {
    //     if($user_id_receive==Auth::id()) return;
    //     $notification = Notification::create([
    //         'user_id' => Auth::id(),
    //         'content' => $content,
    //         'user_id_receive' => $user_id_receive,
    //         'link' => route('blogDetail', $newComment->blog->id),
    //         'key_word'=>'comment-'.$newComment->id
    //     ]);
    //     event(new HasNewNotificationEvent($notification));
    // }
}
