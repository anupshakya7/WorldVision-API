<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryData extends Model
{
    use HasFactory;

    protected $fillable=[
        'indicator_id',
        'countrycode',
        'year',
        'country_score',
        'banded',
        'country_col',
        'country_cat',
        'imputed',
        'remarks',
        'created_by',
        'company_id',
        'political_context'
    ];

    public function scopeFilterWorldVisionData($query){
        return $query->where('political_context',NULL)->where('company_id',1);
    }

    public function scopeFilterElectionData($query){
        return $query->where('political_context',0)->where('company_id',2);
    }

    public function scopeFilterHistoricalDisruptionData($query){
        return $query->where('political_context',1)->where('company_id',2);
    }

    public function scopeFilterDomainScore($query){
        return $query->where('political_context',4)->where('company_id',2);
    }

    public function scopeFilterIndicatorScore($query){
        return $query->where('political_context',2)->where('company_id',2);
    }

    public function scopeFilterVoiceOfPeople($query){
        return $query->where('political_context',3)->where('company_id',2);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    public function country(){
        return $this->belongsTo(Country::class,'countrycode','country_code');
    }
}
