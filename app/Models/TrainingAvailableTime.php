<?php

namespace App\Models;

use Illuminate\Http\Request;

class TrainingAvailableTime extends BaseModel
{
    protected $table = 'training_available_time';
    protected $appends = ['start_time', 'end_time'];

    public function getStartTimeAttribute()
    {
        return $this->time->start_time;
    }

    public function getEndTimeAttribute()
    {
        return $this->time->end_time;
    }
    
    public function training()
    {
        return $this->belongsTo('App\Models\Training');
    }

    public function time()
    {
        return $this->belongsTo('App\Models\AvailableTime', 'available_time_id', 'id');
    }

}
