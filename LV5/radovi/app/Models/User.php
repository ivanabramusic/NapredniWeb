<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Application;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Metoda za provjeru uloge korisnika (kako bi se lakše provjeravale uloge)
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isNastavnik()
    {
        return $this->role->name === 'nastavnik';
    }

    public function isStudent()
    {
        return $this->role->name === 'student';
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'nastavnik_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function appliedTasks()
    {
        return $this->belongsToMany(Task::class, 'applications')->withPivot('prihvaceno')->withTimestamps();
    }
}
