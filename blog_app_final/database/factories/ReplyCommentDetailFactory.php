<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\ReplyCommentDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReplyCommentDetail>
 */
class ReplyCommentDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $comment_id=Comment::inRandomOrder()->first()->id;
        $reply_comment_id=Comment::find($comment_id)->blog->comments->random()->id;
        if($comment_id==$reply_comment_id||ReplyCommentDetail::where('reply_comment_id',$reply_comment_id)->exists())
            return $this->definition();
        return [
            'comment_id'=>$comment_id,
            'reply_comment_id'=>$reply_comment_id
        ];
    }
}
