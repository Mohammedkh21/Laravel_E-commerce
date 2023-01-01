<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'tokenable_id',
        'tokenable_type',
        'updated_at',
        'created_at'
    ];
}
