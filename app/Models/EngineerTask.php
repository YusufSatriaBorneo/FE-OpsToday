<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerTask extends Model
{
    use HasFactory;
    protected $table = 'engineer_tasks';
    protected $fillable = [
        'engineerNumber',
        'ticketNo',
        'title',
        'assignedTo',
        'status',
    ];
}
