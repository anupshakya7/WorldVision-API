<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'countrycode',
        'geocode',
        'geoname',
        'geometry',
        'created_by',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subcountryData(){
        return $this->hasMany(SubCountryData::class,'geocode','geocode');
    }
}
