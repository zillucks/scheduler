<?php

namespace App\Models;

use Illuminate\Http\Request;

class Organization extends BaseModel
{
    protected $table = 'organization';
    protected $appends = ['status'];
    protected $fillable = ['organization_name'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
	    static::creating(function ($model) {
	    	$model->slug = str_slug($model->organization_name);
        });
        static::updating(function ($model) {
	    	$model->slug = str_slug($model->organization_name);
	    });
    }

    protected function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function getStatusAttribute()
    {
        return $this->organization_status ? "<i class='fas fa-check text-success'></i> Aktif" : "<i class='fas fa-times text-danger'></i> Tidak Aktif";
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->has('organization_name') && !empty($request->get('organization_name'))) {
            $search = strtolower($request->get('organization_name'));
            $query->whereRaw('lower(organization_name) like ?', '%'. $search .'%');
        }
        return $query;
    }
}