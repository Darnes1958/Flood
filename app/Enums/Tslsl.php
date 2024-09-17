<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum Tslsl: int implements HasLabel
{

  case عرض = 1;
  case عرض_صور = 2;
    case اعداد = 3;
  case عئلات = 4;
  case عناوين = 5;
  case ضيوف = 6;
    case انقاذ = 7;
    case عمل = 8;
    case الدول = 9;
    case رسوم = 10;





  public function getLabel(): ?string
  {
    return $this->name;
  }
}
