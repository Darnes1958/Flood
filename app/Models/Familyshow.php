<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familyshow extends Model
{
    public function Victim(){
        return $this->hasMany(Victim::class);
    }
    public function Family(){
        return $this->hasMany(Familyshow::class);
    }
    public function Big_family(){
        return $this->belongsTo(BigFamily::class);
    }
}
