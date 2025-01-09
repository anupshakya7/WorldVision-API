<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryDomainData extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'countrycode',
        'year',
        'score',
        'domain_result',
        'trend_result',
        'trend_percentage',
        'shifts_governance',
        'created_by',
        'company_id'
    ];

    public function scopeFilterData($query){
        return $query->where('company_id',2);
    }

    public function domain(){
        return $this->belongsTo(Indicator::class,'domain_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function country(){
        return $this->belongsTo(Country::class,'countrycode','country_code');
    }
}
