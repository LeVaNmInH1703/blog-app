<?php

namespace App\Services;

use App\Jobs\Translate;
use App\Repositories\CommentRepository;
use Illuminate\Support\Str;

class CommentService
{
    protected $commentRepository, $mediaService;
    public function __construct(CommentRepository $commentRepository, MediaService $mediaService)
    {
        $this->commentRepository = $commentRepository;
        $this->mediaService = $mediaService;
    }
    public function setBaseComment($comment)
    {
        if (!$comment->key_translate) {
            $comment->key_translate = hashToTranslate($comment->content);
            $comment->save();
        }

        $baseTranslate = getTranslateByKey($comment->key_translate);
        if ($baseTranslate) {
            $comment->originalLanguage = $baseTranslate['originalLanguage'];
            $comment->translatedText = $baseTranslate['translatedText'];
        } else {
            Translate::dispatch($comment->content, session('locale'));
        }
        $comment->isTranslated = $baseTranslate ? true : false;
    }
    public function getRootCommentsByBlogId($blogId)
    {
        $rootComments = $this->commentRepository->getRootCommentsByBlogId($blogId);
        foreach ($rootComments as $comment) {
            $this->setBaseComment($comment);
        }
        return $rootComments;
    }
    public function setComment($id)
    {
        $comment = $this->commentRepository->getCommentById($id);
        $this->setBaseComment($comment);

        return $comment;
    }
    public function getWithEmojis($id)
    {
        return $this->commentRepository->getWithEmojis($id);
    }
    public function getEmojisCount($id)
    {
        return $this->commentRepository->getEmojisCount($id);
    }
    public function getMyEmoji($id)
    {
        return $this->commentRepository->getMyEmoji($id);
    }

    public function createComment($request, $blog, $comment)
    {
        $newComment = $this->commentRepository->create($request, $blog, $comment);
        $this->mediaService->saveFilesWithModel($request->file('filesImage'), $newComment,'comment');
        return $newComment;
    }
    public function getCommentsCount($id)
    {
        return $this->commentRepository->getCommentsCount($id);
    }
    public function getReplies($commentId)
    {
        $replies = $this->commentRepository->getReplies($commentId);
        foreach ($replies as $reply) {
            $this->setBaseComment($reply);
        }
        return $replies;
    }
}
