<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Ovdje definiraš odnos s korisnicima (korisnici mogu imati samo jednu ulogu)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
