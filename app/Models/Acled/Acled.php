<?php

namespace App\Models\Acled;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acled extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'aclied';
}
