<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationLink extends Mailable
{
    use Queueable, SerializesModels;

    public string $verifyUrl;
    public string $userName;

    public function __construct(string $verifyUrl, string $userName = 'طالبنا العزيز')
    {
        $this->verifyUrl = $verifyUrl;
        $this->userName  = $userName;
    }

    public function build()
    {
        return $this->subject('تفعيل البريد الإلكتروني — المنهج الأكاديمي')
                    ->view('emails.verify_link')
                    ->with([
                        'verifyUrl' => $this->verifyUrl,
                        'userName'  => $this->userName,
                    ]);
    }
}
