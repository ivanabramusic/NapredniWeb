<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'naziv',
        'naziv_en',
        'zadatak',
        'tip_studija',
        'nastavnik_id',
    ];

    public function nastavnik()
    {
        return $this->belongsTo(User::class, 'nastavnik_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'applications')->withPivot('prihvaceno')->withTimestamps();
    }
}
