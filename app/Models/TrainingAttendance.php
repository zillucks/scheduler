<?php

namespace App\Models;

use Illuminate\Http\Request;
use Carbon\Carbon;

class TrainingAttendance extends BaseModel
{
    protected $table = 'training_attendance';
    protected $appends = ['location', 'status', 'status-class'];

    protected $fillable = [
        'reservation_id',
        'training_id',
        'training_attendance_date',
        'training_attendance_time_id',
        'training_attendance_status'
    ];

    public function getLocationAttribute()
    {
        return "{$this->training->class->class_name}, {$this->training->site->site_name}";
    }

    public function getStatusAttribute()
    {
        return $this->training_attendance_status ? "<i class='fas fa-check text-success'></i> Selesai" : "<i class='fas fa-exclamation-triangle text-warning'></i> Belum / Sedang Berjalan";
    }

    public function getStatusClassAttribute()
    {
        return $this->training_attendance_status ? "bg-success" : "";
    }

    public function canRegister()
    {
        $date = Carbon::now();
        return $this->training_attendance_date == $date->toDateString() && !$this->training_attendance_status;
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->has('status') && !is_null($request->get('status'))) {
            $query->where('training_attendance_status', $request->get('status'));
        }

        if ($request->has('attendance_date') && !empt($request->get('attendance_date'))) {
            $query->whereDate('attendance_date', $request->get('attendance_date'));
        }

        if ($request->has('training_name') && !empty($request->get('training_name'))) {
            $query->whereHas('training', function ($training) use($request) {
                $training->filter($request);
            });
        }

        return $query;
    }

    public function training()
    {
        return $this->belongsTo('App\Models\Training');
    }

    public function time()
    {
        return $this->belongsTo('App\Models\AvailableTime', 'training_attendance_time_id');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\TrainingAttendanceUser', 'training_attendance_id');
    }
}
