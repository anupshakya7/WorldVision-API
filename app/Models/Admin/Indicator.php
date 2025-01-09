<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'domain_id',
        'variablename_long',
        'variablename',
        'vardescription',
        'varunits',
        'is_more_better',
        'transformation',
        'lower',
        'upper',
        'sourcelinks',
        'subnational',
        'national',
        'imputation',
        'level',
        'created_by',
        'company_id'
    ];

    public function scopeFilterIndicator($query){
        return $query->where('company_id',auth()->user()->company_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function domains(){
        return $this->belongsTo(Indicator::class,'domain_id');
    }

    public function domainData(){
        return $this->hasMany(CountryDomainData::class,'domain_id');
    }

    public function source()
    {
        return $this->hasMany(Source::class, 'indicator_id');
    }
}
