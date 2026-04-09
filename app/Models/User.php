<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 🔥 pakai default id (tidak perlu primaryKey lagi)

    protected $fillable = [
        'role_id',
        'name',
        'username',
        'email',
        'password',
        'agency',
        'phone_number',
        'is_approved'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_approved' => 'boolean'
        ];
    }

    // =========================
    // RELATION
    // =========================

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id'); // ✅ FIX
    }

    public function approval()
    {
        return $this->hasOne(Approval::class, 'id_user', 'id'); // (optional nanti kita rapihin)
    }
}