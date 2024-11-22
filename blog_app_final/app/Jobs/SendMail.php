<?php

namespace App\Jobs;

use App\Mail\ForgotPasswordEmail;
use App\Mail\VerifyMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Queueable;

    public $type,$data,$toEmail;
    public function __construct($type,$toEmail,$data)
    {
        $this->type=$type;
        $this->data=$data;
        $this->toEmail=$toEmail;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->type=='forgot password'){
            Mail::to($this->toEmail)->send(
                new ForgotPasswordEmail($this->data['user'],$this->data['url']));
            }
        else if($this->type=='verify email'){
            Mail::to($this->toEmail)->send(
                new VerifyMail($this->data['verificationUrl'],$this->data['user']));
            }
    }
}
