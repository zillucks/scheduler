<?php

namespace App\Models;

use Illuminate\Http\Request;

class ReservationUser extends BaseModel
{
    protected $table = 'reservation_user';

    public function reservation()
    {
        return $this->belongsTo('App\Models\Reservation');
    }

    public function identity()
    {
        return $this->belongsTo('App\Models\Identity', 'user_identity_id');
    }
}
