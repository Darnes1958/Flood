<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    public function Area(){
      return $this->belongsTo(Area::class);
    }
    public function Victim(){
      return $this->hasMany(Victim::class);
    }
}
