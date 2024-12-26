<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseEndNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $course;
    public $confirmUrl;
    public $cancelUrl;

    public function __construct(Course $course, $token)
    {
        $this->course = $course;
        $this->confirmUrl = route('email.confirm', ['token' => $token]);
        $this->cancelUrl = route('email.cancel', ['token' => $token]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kursteilnahme ' . $this->course->courseType->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown : 'emails.course_end_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
