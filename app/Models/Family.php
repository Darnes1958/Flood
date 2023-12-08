<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;


    public function Victim(){
      return $this->hasMany(Victim::class);
    }
    public function Tribe(){
      return $this->belongsTo(Tribe::class);
    }
}
