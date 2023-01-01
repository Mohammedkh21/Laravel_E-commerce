<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistorMail extends Model
{
    use HasFactory;

    protected $table='vsitor_mails';

    protected $fillable =[
      'id',
      'email',
    ];
}
