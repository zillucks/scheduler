<?php

namespace App\Models;

use Illuminate\Http\Request;

class Department extends BaseModel
{
    protected $table = 'department';
    protected $appends = ['status'];
    protected $fillable = ['department_name', 'department_status'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
	    static::creating(function ($model) {
	    	$model->slug = str_slug($model->department_name);
        });
        
        static::updating(function ($model) {
            $model->slug = str_slug($model->department_name);
        });
    }

    protected function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function getStatusAttribute()
    {
        return $this->department_status ? "<i class='fas fa-check text-success'></i> Aktif" : "<i class='fas fa-times text-danger'></i> Tidak Aktif";
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->has('department_name') && !empty($request->get('department_name'))) {
            $search = strtolower($request->get('department_name'));
            $query->whereRaw('lower(department_name) like ?', '%'. $search .'%');
        }
        return $query;
    }
}
