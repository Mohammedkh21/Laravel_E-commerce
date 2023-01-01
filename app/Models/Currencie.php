<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currencie extends Model
{
    use HasFactory;
    protected $table ='currencies';
    protected $fillable=[
        'id',
        'name',
        'cc',
        'symbol',

    ];
    public $timestamps=false;
}
