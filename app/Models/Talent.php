<?php

namespace App\Models;

use App\Enums\talentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
  protected $table='talent';
  public function Victalent(){
   return $this->hasMany(VicTalent::class);
  }
    public function Victim()
    {
        return $this->hasManyThrough('App\Models\Victim', 'App\Models\VicTalent');
    }
    protected $casts =[
        'talentType'=>talentType::class,
        ];
}
