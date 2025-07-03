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

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
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
}
