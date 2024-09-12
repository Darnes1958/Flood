<?php

namespace App\Models;

use App\Enums\talentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
  protected $table='talent';
  public function Victim(){
   return $this->hasMany(VicTalent::class);
  }
    protected $casts =[
        'talentType'=>talentType::class,
        ];
}
