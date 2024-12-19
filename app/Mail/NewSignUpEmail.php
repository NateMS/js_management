<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewSignUpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Course $course;
    public User $user;

    public function __construct(Course $course, User $user)
    {
        $this->course = $course;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Neue J&S-Kursanmeldung',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sign_up',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
