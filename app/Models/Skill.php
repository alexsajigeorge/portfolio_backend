<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class Skill extends Model
{
    use HasApiTokens, HasFactory;

    public function projects(){
        return $this->belongsToMany(Project::class, 'project_skill_mapings');
    }

    protected $fillable = [
        'user_id',
        'skill_name',
        'description',
        'proficency_lvl',
        'icon',
    ];
}
