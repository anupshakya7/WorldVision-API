<?php

namespace App\Models\Acled;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acled extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'aclied';

    protected $fillable =[
        'event_id_cnty',
        'event_date',
        'year',
        'time_precision',
        'disorder_type',
        'event_type',
        'sub_event_type',
        'actor1',
        'assoc_actor_1',
        'inter1',
        'actor2',
        'assoc_actor_2',
        'inter2',
        'interaction',
        'civilian_targeting',
        'iso',
        'region',
        'country',
        'admin1',
        'admin2',
        'admin3',
        'location',
        'latitude',
        'longitude',
        'geo_precision',
        'source',
        'source_scale',
        'notes',
        'fatalities',
        'timestamp',
    ];
}
