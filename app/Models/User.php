<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\UserType;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens;
 use HasFactory, Notifiable, HasRoles;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasProfilePhoto;
    
    use TwoFactorAuthenticatable;

protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'last_login_at',
          'receives_notifications'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean'
    ];

       protected $appends = [
        'profile_photo_url',
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

     public function isAdministrador(): bool
    {
        return $this->type === UserType::ADMINISTRADOR;
    }

    public function isDoctor(): bool
    {
        return $this->type === UserType::DOCTOR;
    }

    public function isNurse(): bool
    {
        return $this->type === UserType::NURSE;
    }

    public function isManager(): bool
    {
        return $this->type === UserType::MANAGER;
    }

    public function isTecnico():bool{
        return $this->type===UserType::TECNICO;
    }


    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function respondedRequests()
    {
        return $this->hasMany(Request::class, 'responded_by');
    }

    public function notifications()
{
    return $this->hasMany(Notification::class);
}

public function unreadNotifications()
{
    return $this->notifications()->where('read', false);
}

 // Escopo para pesquisa
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function($query) use ($term) {
            $query->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term);
        });
    }

    // Escopo para filtrar por status
    public function scopeActive($query, $status)
    {
        if ($status !== null) {
            return $query->where('is_active', $status);
        }
        return $query;
    }

    // Escopo para filtrar por função
    public function scopeWithRole($query, $role)
    {
        if ($role) {
            return $query->role($role);
        }
        return $query;
    }
}
