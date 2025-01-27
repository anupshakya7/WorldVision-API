<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'indicator_id',
        'source',
        'data_level',
        'impid',
        'units',
        'description',
        'url',
        'link',
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

    public function scopeFilterSource($query){
        return $query->where('company_id',auth()->user()->id);
    }
}
