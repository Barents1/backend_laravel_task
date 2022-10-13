<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
     // atributos disponibles para user
    protected $fillable = [
        'identification',
        'firstname',
        'lastname',
        'birthday',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // método para retornar el identificador del JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // método para generar el Json Web Token
    public function getJWTCustomClaims()
    {
        return [];
    }

    // método de la relación de uno a muchos con la entidad de task
    public function task()
    {
        return $this->hasMany('App\Models\Task');
    }
}
