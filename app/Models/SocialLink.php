<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class SocialLink extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user_id',
        'platform_name',
        'platform_url',
        'icon',
    ];
}
