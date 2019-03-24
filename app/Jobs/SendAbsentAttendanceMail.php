<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\TrainingAttendance;
use App\Models\TrainingAttendanceUser;
use App\Mail\ParticipantPresence;
use App\Mail\ParticipantPresenceManager;
use Mail;

class SendAbsentAttendanceMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendance;
    protected $participant;
    protected $sendTo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TrainingAttendance $attendance, TrainingAttendanceUser $participant, $sendTo)
    {
        $this->attendance = $attendance;
        $this->participant = $participant;
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
            $mail = new ParticipantAbsentManager($this->attendance, $this->participant);
            $address = $this->participant->reservation->manager_email;
        }
        else {
            $mail = new ParticipantAbsent($this->attendance, $this->participant);
            $address = $this->participant->identity->email;
        }
        
        Mail::to($address)->send($mail);
    }
}
