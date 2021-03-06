<?php

namespace App\Models;

use Illuminate\Http\Request;

class Unit extends BaseModel
{
    protected $table = 'unit';
    protected $appends = ['status'];
    protected $fillable = ['unit_name'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
	    static::creating(function ($model) {
	    	$model->slug = str_slug($model->unit_name);
        });
        static::updating(function ($model) {
	    	$model->slug = str_slug($model->unit_name);
	    });
    }

    protected function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function getStatusAttribute()
    {
        return $this->unit_status ? "<i class='fas fa-check text-success'></i> Aktif" : "<i class='fas fa-times text-danger'></i> Tidak Aktif";
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->has('unit_name') && !empty($request->get('unit_name'))) {
            $search = strtolower($request->get('unit_name'));
            $query->whereRaw('lower(unit_name) like ?', '%'. $search .'%');
        }
        return $query;
    }
}
