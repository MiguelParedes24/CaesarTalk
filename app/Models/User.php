<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastName',
        'email',
        'userName',
        'password',
        'last_login_at',
        'last_login_before',
        'new_messages_count',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'last_login_before' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación de un usuario con los mensajes enviados
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Relación de un usuario con los mensajes recibidos
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function getLastLoginAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    protected $appends = ['welcome_message'];

    public function getWelcomeMessageAttribute()
    {
        return session('welcome_message');
    }
}
