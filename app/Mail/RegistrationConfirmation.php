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

class RegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Course $course;
    public User $user;
    public User $coach;

    public function __construct(Course $course)
    {
        $this->course = $course;
        $this->coach = User::where('is_js_coach', true)->first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'J&S-Kurs Anmeldebestätigung',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registered',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
