<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\LandingPageVisit; // Or pass data as an array

class LandingPageVisited extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The landing page visit instance or data.
     *
     * @var array // Or LandingPageVisit
     */
    public array $visitData;

    /**
     * Create a new message instance.
     *
     * @param array $visitData // Or LandingPageVisit $visit
     */
    public function __construct(array $visitData)
    {
        $this->visitData = $visitData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address', 'noreply@example.com'), // Use configured from address
            subject: 'Notification: Landing Page Visited',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.landingpage.visited', // Path to the Blade view
            with: [
                'visitData' => $this->visitData,
            ],
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
