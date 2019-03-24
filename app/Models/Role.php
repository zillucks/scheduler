<?php

namespace App\Models;

use Illuminate\Http\Request;

class Role extends BaseModel
{
    protected $table = 'roles';

    protected function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'user_role', 'role_id', 'user_id')->withTimestamps();
    }
}
