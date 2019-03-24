<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ParticipantPresenceManager extends Mailable
{
    use Queueable, SerializesModels;

    public $attendance;
    public $participant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attendance, $participant)
    {
        $this->attendance = $attendance;
        $this->participant = $participant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.attendances.presence-manager');
    }
}
