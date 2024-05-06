<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum Marry: int implements HasLabel
{
  case اعزب = 1;
  case متزوج = 2;
  case متزوجة = 3;
  case مطلق = 4;
  case مطلقة = 5;
  case أرمله = 6;
  case انسة = 7;



  public function getLabel(): ?string
  {
    return $this->name;
  }
}
