<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum Subjects: int implements HasLabel
{
  case قبل_الكارثة = 1;
  case أثناء_الكارثة = 2;
  case بعد_الكارثة = 3;
  case روايات_الناجين = 4;



  public function getLabel(): ?string
  {
    return $this->name;
  }
}
