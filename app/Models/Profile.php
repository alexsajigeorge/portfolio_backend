<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Profile extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'bio',
        'intro',
        'contact_no',
        'avatar',
    ];
}
