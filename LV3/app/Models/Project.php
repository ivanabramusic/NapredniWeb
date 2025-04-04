<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'description',
        'price',
        'completed_tasks',
        'start_date',
        'end_date',
        'leader_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_project');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
