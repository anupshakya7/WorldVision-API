<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'region_id',
        'countrycode',
        'geocode',
        'latitude',
        'longitude',
        'project_title',
        'project_overview',
        'link',
        'indicator_id',
        'subindicator_id',
        'created_by',
        'company_id',
    ];

    public function region(){
        return $this->belongsTo(Country::class,'region_id');
    }

    public function country(){
        return $this->belongsTo(Country::class,'countrycode','country_code');
    }

    public function subcountry(){
        return $this->belongsTo(SubCountry::class,'geocode','geocode');
    }

    public function domain(){
        return $this->belongsTo(Indicator::class,'indicator_id');
    }

    public function indicator(){
        return $this->belongsTo(Indicator::class,'subindicator_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
}
