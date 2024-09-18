<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraMiles extends Model
{
    use HasFactory;
    protected $table = 'engineer_extra_miles';
    protected $fillable = ['engineer_id', 'engineer_name', 'start_date', 'end_date', 'activity_name'];
}
