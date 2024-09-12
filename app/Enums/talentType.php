<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum talentType: int implements HasLabel
{

  case مواهب = 1;
  case دارنس = 2;
  case الافريقي = 3;
  case الهلال_الاحمر = 4;
  case الكشافة = 5;





  public function getLabel(): ?string
  {
    return $this->name;
  }
}
