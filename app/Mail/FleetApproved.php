<?php

namespace App\Mail;

use App\Models\FleetHire;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FleetApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public FleetHire $fleetHire) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Deployment Authorization: '.$this->fleetHire->fleet->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.fleet.approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
