<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Skill extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user_id',
        'skill_name',
        'description',
        'proficency_lvl',
        'icon',
    ];
}
