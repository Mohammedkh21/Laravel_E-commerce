<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory ,SoftDeletes;


    protected $fillable = [

        'name',
        'title',
        'seller_id',
        'category_id',
        'currencie_id',
        'quantity',
        'photo',
        'price',
        'created_at',
        'updated_at',
        'status'

    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function currencie(){
        return $this->belongsTo(Currencie::class);
    }

    public function seller(){
        return $this->belongsTo(Seller::class,'seller_id','id');
    }

    public function image(){
        return $this->morphOne(Image::class,'imageable');
    }


}
