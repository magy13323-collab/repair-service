<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    // Указываем, какие поля можно заполнять через форму
    protected $fillable = [
        'clientName',
        'phone',
        'address',
        'problemText',
        'status',
        'assignedTo',
    ];

    // Связь с пользователем (мастером)
    public function technician()
    {
        return $this->belongsTo(User::class, 'assignedTo');
    }
}