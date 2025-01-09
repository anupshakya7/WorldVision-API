<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'country_col_order',
        'country_leg_col',
        'subcountry_col_order',
        'subcountry_leg_col',
        'created_by',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
