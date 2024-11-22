<?php

namespace App\Services;

use App\Jobs\Translate;
use App\Repositories\BlogRepository;
use Illuminate\Support\Facades\Auth;

class BlogService
{
    protected $blogRepository;
    protected $commentService,$mediaService;

    public function __construct(BlogRepository $blogRepository,MediaService $mediaService, CommentService $commentService)
    {
        $this->blogRepository = $blogRepository;
        $this->commentService = $commentService;
        $this->mediaService = $mediaService;
    }

    public function getBlogs($limit = 5)
    {
        $blogs = $this->blogRepository->getBlogsWithDetails($limit);
        foreach ($blogs as $blog) {
           $this->setBaseBlog($blog);
        }
        // dd($blogs);
        return $blogs;
    }
    public function setBaseBlog(&$blog){
        if (!$blog->key_translate) {
            $blog->key_translate = hashToTranslate($blog->content);
            $blog->save();
        }

        $baseTranslate = getTranslateByKey($blog->key_translate);
        if ($baseTranslate) {
            $blog->originalLanguage = $baseTranslate['originalLanguage'];
            $blog->translatedText = $baseTranslate['translatedText'];
        } else {
            Translate::dispatch($blog->content, session('locale'));
        }
        $blog->isTranslated = $baseTranslate?true:false;
    }
    public function getBlogDetail($id){
        $blog=$this->blogRepository->getBlogWithDetails($id);
        $this->setBaseBlog($blog);
        $blog->rootComments = $this->commentService->getRootCommentsByBlogId($id);
        
        return $blog;
    }
    public function getWithEmojis($id){
        return $this->blogRepository->getWithEmojis($id);
    }
    public function getEmojisCount($id){
        return $this->blogRepository->getEmojisCount($id);
    }
    public function getCommentsCount($id){
        return $this->blogRepository->getCommentsCount($id);
    }
    public function getMyEmoji($id){
        return $this->blogRepository->getMyEmoji($id);
    }
    public function createBlog($request){
        $privacy=$request->input('privacy');
        $datetime=$request->input('datetime');
        $content=$request->input('content');
        $medias=$request->file('files');
        //tạo bản ghi trong csdl
        $newBlog=$this->blogRepository->createBlog($content);
        $this->mediaService->saveFilesWithModel($medias,$newBlog,'blog');
        return $newBlog;
    }
}
