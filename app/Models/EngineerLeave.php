<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerLeave extends Model
{
    use HasFactory;

    protected $table = 'engineer_leaves';
    protected $fillable = ['engineer_id', 'engineer_name', 'start_date', 'end_date'];
}
