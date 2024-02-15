<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Jorunal extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'img_url',
        'created_date',
    ];
}
