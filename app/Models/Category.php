<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table ='categorys';
    protected $fillable = [
        'id',
        'name',
        'status',

    ];
    protected $hidden =[
        'created_at',
        'updated_at'
    ];

    public function product(){
        return $this->hasMany(Product::class,'category_id','id');
    }
}
