<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Seller extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at',
        'status'

    ];


    protected $hidden = [
        'password',


    ];

    public function product(){
        return $this->hasMany(Product::class,'seller_id','id');
    }

}
