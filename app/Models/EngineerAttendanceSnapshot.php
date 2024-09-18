<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerAttendanceSnapshot extends Model
{
    use HasFactory;

    protected $table = 'engineer_attendance_snapshots';

    protected $fillable = [
        'engineer_id',
        'status',
        'check_in_time',
    ];
}