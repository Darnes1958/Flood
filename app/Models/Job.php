<?php

namespace App\Models;

use App\Enums\jobType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function Victim(){
      return $this->hasMany(Victim::class);
    }
    protected $casts =[
        'jobType'=>jobType::class,
        ];
}
