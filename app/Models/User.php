<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "username",
        "firstname",
        "lastname",
        "email",
        "bio",
        "contact",
        "age",
        "gender",
        "matchgender",
        "profile",
        "password",
        "ishidden",
        "date_verified",
    ];

    public function Personality()
    {
        return $this->hasOne(Personality::class);
    }
    public function Block()
    {
        return $this->hasMany('app\Models\Block.php','blockedID','id');
    }
}
