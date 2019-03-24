<?php

namespace App\Models;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailableTime extends BaseModel
{
    protected $table = 'available_time';
    protected $appends = ['status', 'title'];

    public function getStatusAttribute()
    {
        return $this->time_status ? "<i class='fas fa-check text-success'></i> Aktif" : "<i class='fas fa-times text-danger'></i> Tidak Aktif";
    }

    public function getTitleAttribute()
    {
        return $this->attributes['title'] = "{$this->start_time} s/d {$this->end_time}";
    }

    public function getStartTimeAttribute()
    {
        $start_time = Carbon::createFromFormat('H:i:s', $this->attributes['start_time']);
        return $start_time->format('H:i');
    }

    public function getEndTimeAttribute()
    {
        $end_time = Carbon::createFromFormat('H:i:s', $this->attributes['end_time']);
        return $end_time->format('H:i');
    }

    public function training_available_times()
    {
        return $this->hasMany('App\Models\TrainingAvailableTime', 'available_time_id', 'id');
    }

    public function reservations()
    {
        return $this->hasMany('App\Models\Reservation', 'reservation_time_id', 'id');
    }
}
