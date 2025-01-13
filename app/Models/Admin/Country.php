<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'country_code',
        'parent_id',
        'latitude',
        'longitude',
        'bounding_box',
        'geometry',
        'level',
        'ati',
        'created_by',
        'company_id'
    ];
    
    public function scopeFilterATICountry($query){
        return $query->where('ati',1);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parentData(){
        return $this->belongsTo(Country::class,'parent_id');
    }

    public function countryData(){
        return $this->hasMany(CountryData::class,'countrycode','country_code');
    }


}
