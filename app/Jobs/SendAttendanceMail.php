<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\TrainingAttendance;
use App\Models\TrainingAttendanceUser;
use App\Models\Identity;
use App\Mail\ParticipantPresence;
use App\Mail\ParticipantPresenceManager;
use Mail;

class SendAttendanceMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $destination;
    protected $attendance;
    protected $participant;
    public $identity;
    protected $sendTo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($destination, TrainingAttendance $attendance, TrainingAttendanceUser $participant, Identity $identity, $sendTo)
    {
        $this->destination = $destination;
        $this->attendance = $attendance;
        $this->participant = $participant;
        $this->identity = $identity;
        $this->sendTo = $sendTo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->sendTo == 'manager') {
            $mail = new ParticipantPresenceManager($this->attendance, $this->participant);
        }
        else {
            $mail = new ParticipantPresence($this->attendance, $this->participant);
        }
        
        Mail::to($this->destination)->send($mail);
    }
}
