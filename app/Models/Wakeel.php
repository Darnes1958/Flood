<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wakeel extends Model
{
  public function Hadam(){
    return $this->hasMany(Hadam::class);
  }
}
