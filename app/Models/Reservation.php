<?php

namespace App\Models;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class Reservation extends BaseModel
{
    protected $table = 'reservation';
    protected $appends = ['status_class', 'user_count'];

    public function scopeFilter($query, Request $request)
    {
        if ($request->has('full_name') && !empty($request->get('full_name'))) {
            $query->whereHas('identity', function ($identity) use($request) {
                $search = strtolower($request->get('full_name'));
                $identity->whereRaw('lower(full_name) like ?', '%'. $search .'%');
            });
        }

        if ($request->has('email') && !empty($request->get('email'))) {
            $query->whereHas('identity', function ($identity) use($request) {
                $identity->where('email', $request->get('email'));
            });
        }

        if ($request->has('site_name') && !empty($request->get('site_name'))) {
            $query->whereHas('training.site', function ($site) use($request) {
                $search = strtolower($request->get('site_name'));
                $site->whereRaw('lower(site_name) like ?', '%'. $search .'%');
            });
        }

        if (!\Auth::user()->isAdmin()) {
            $query->whereHas('reservation_users', function ($user) {
                $user->where('user_identity_id', \Auth::user()->identity->id);
            });
        }

        return $query;
    }

    public function getStatusClassAttribute()
    {
        $class = '';
        switch ($this->reservation_status)
        {
            case 'pending':
            default:
                $class = '';
            break;
            case 'approved':
                $class = 'bg-success';
            break;
            case 'declined':
                $class = 'bg-danger';
            break;
        }

        return $class;
    }

    public function getUserCountAttribute()
    {
        return $this->reservation_users()->count();
    }

    public function canModify()
    {
        $date = Carbon::now();
        $reservation_date = Carbon::parse($this->reservation_date);

        $diff = $reservation_date->diffInDays($date);

        if ($diff < 5) {
            return false;
        }

        return ($this->user_identity_id === Auth::user()->identity_id) || Auth::user()->isAdmin();
    }
    
    public function training()
    {
        return $this->belongsTo('App\Models\Training');
    }

    public function reservation_users()
    {
        return $this->hasMany('App\Models\ReservationUser', 'reservation_id', 'id');
    }

    public function identity()
    {
        return $this->belongsTo('App\Models\Identity', 'user_identity_id', 'id');
    }

    public function time()
    {
        return $this->belongsTo('App\Models\AvailableTime', 'reservation_time_id', 'id');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\TrainingAttendanceUser', 'reservation_id');
    }

}
