<?php

namespace App\Models;

use Database\Factories\UserFactory;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'active',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',

            'password' => 'hashed',

            'active' => 'boolean',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | TEACHER RELATION
    |--------------------------------------------------------------------------
    */

    public function teacher()
    {
        return $this->hasOne(
            Teacher::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT RELATION
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->hasOne(
            Student::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }


    public function isStudent(): bool
    {
        return $this->role === 'student';
    }


    /*
    |--------------------------------------------------------------------------
    | ACCOUNT STATUS
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->active === true;
    }
}