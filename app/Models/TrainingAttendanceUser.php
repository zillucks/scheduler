<?php

namespace App\Models;

use Illuminate\Http\Request;
use Carbon\Carbon;

class TrainingAttendanceUser extends BaseModel
{
    protected $table = 'training_attendance_user';
    protected $appends = ['status', 'status_class'];
    protected $fillable = ['reservation_id', 'identity_id'];

    public function getStatusAttribute()
    {
        $status = '';
        switch($this->training_attendance_user_status)
        {
            case 'awaiting':
            default:
                $status = '';
            break;
            case 'present':
                $status = 'Hadir';
            break;
            case 'absent':
                $status = 'Tidak Hadir';
            break;
        }

        return $status;
    }

    public function getStatusClassAttribute()
    {
        $class = '';
        switch($this->training_attendance_user_status)
        {
            case 'awaiting':
            default:
                $class = '';
            break;
            case 'present':
                $class = 'bg-success';
            break;
            case 'absent':
                $class = 'bg-danger';
            break;
        }

        return $class;
    }

    public function shouldSendMail()
    {
        return ($this->training_attendance_user_status == 'present' || $this->training_attendance_user_status == 'present');
    }

    public function training_attendance()
    {
        return $this->belongsTo('App\Models\TrainingAttendance', 'training_attendance_id', 'id');
    }

    public function identity()
    {
        return $this->belongsTo('App\Models\Identity');
    }

    public function reservation()
    {
        return $this->belongsTo('App\Models\Reservation');
    }
}
