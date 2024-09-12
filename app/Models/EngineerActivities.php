<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerActivities extends Model
{
    use HasFactory;
    protected $table = 'engineer_activities';
    protected $fillable = [
        'engineer_id',
        'ticketNo',
        'status',
        'isOnProgress',
        'completion_time',
    ];
}
