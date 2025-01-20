<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCountryData extends Model
{
    use HasFactory;

    protected $fillable = [
        'indicator_id',
        'geocode',
        'year',
        'raw',
        'banded',
        'in_country_rank',
        'admin_cat',
        'admin_col',
        'source_id',
        'statements',
        'created_by',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    public function subcountry(){
        return $this->belongsTo(SubCountry::class,'geocode','geocode');
    }

    public function source(){
        return $this->belongsTo(Source::class,'source_id');
    }
}
