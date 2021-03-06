<?php

namespace App\Models;

use Illuminate\Http\Request;

class Directorate extends BaseModel
{
    protected $table = 'directorate';
    protected $appends = ['status'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
	    static::creating(function ($model) {
	    	$model->slug = str_slug($model->directorate_name);
	    });
    }

    protected function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function getStatusAttribute()
    {
        return $this->directorate_status ? "<i class='fas fa-check text-success'></i> Aktif" : "<i class='fas fa-times text-danger'></i> Tidak Aktif";
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->has('directorate_name') && !empty($request->get('directorate_name'))) {
            $search = strtolower($request->get('directorate_name'));
            $query->whereRaw('lower(directorate_name) like ?', '%'. $search .'%');
        }
        return $query;
    }
}
