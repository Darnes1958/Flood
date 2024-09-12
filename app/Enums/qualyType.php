<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum qualyType: int implements HasLabel
{

  case ابتدائي = 1;
  case اعدادي = 2;
    case ثانوي = 3;
  case بكالوريوس = 4;
  case ليسانس = 5;
  case ماجستير = 6;
    case دكتوراه = 7;
    case معهد_عالي = 8;
    case معهد_متوسط = 9;





  public function getLabel(): ?string
  {
    return $this->name;
  }
}
