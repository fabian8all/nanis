<?php

namespace App\Mail;

use App\Models\SupervisorInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupervisorInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupervisorInvitation $invitation,
        public string $token,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject('Invitación para supervisar prácticas profesionales'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.supervisor-invitation',
            with: [
                'invitationUrl' => route('supervisor.invitations.accept', $this->token),
            ],
        );
    }
}
