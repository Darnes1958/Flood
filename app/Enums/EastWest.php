<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum EastWest: int implements HasLabel
{

  case شرق_الوادي= 1;
  case غرب_الوادي = 2;
    case وادي_الناقة = 3;
  case وادي_درنه = 4;
  case غير_محدد = 5;





  public function getLabel(): ?string
  {
    return str_replace('_',' ',$this->name);
  }
}
