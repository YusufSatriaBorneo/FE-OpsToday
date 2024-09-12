<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;
    protected $table = 'email_logs';

        protected $fillable = [
        'sent_at',
        'recipient',
        'subject',
        'content',
    ];
    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
