<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personality extends Model
{
    use HasFactory;

    protected $fillable = [
        "username",
        "firstname",
        "lastname",
        "bio",
        "profile",
        "date_verified",
        "password"
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }
}