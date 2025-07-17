<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invitation $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this->subject('Invitation à rejoindre un projet')
            ->view('emails.project_invitation')
            ->with([
                'invitation' => $this->invitation,
                'acceptUrl' => route('invitations.accept', $this->invitation->token),
            ]);
    }
}
