<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Task extends Model
{
    protected $fillable = [
        'title',
        'status',
        'discription'
    ];
}
