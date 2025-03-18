<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = ['subject', 'body', 'shift', 'sender_id', 'receiver_id', 'sent_at', 'received_at', 'is_read'];

    protected $casts = [
        'sent_at' => 'datetime',
        'received_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    // Relación: El mensaje pertenece al remitente
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relación: El mensaje pertenece al destinatario
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
