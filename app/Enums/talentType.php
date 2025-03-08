<?php

namespace App\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum talentType: int implements HasLabel,HasColor
{

  case مواهب = 1;
  case دارنس = 2;
  case الافريقي = 3;
  case الهلال_الاحمر = 4;
  case الكشافة = 5;
  case حغاظ_وأئمة = 6;


public function getColor(): string|array|null
{
    return match ($this) {
        self::دارنس => 'primary',
        self::الافريقي => 'success',
        self::الهلال_الاحمر => 'danger',
        self::الكشافة => 'Fuchsia',
        self::مواهب => 'blue',
        self::حغاظ_وأئمة => 'blue',
    };
}


    public function getLabel(): ?string
  {

      return match ($this) {
          self::مواهب => 'مواهب',
          self::دارنس => 'دارنس',
          self::الكشافة => 'الكشافة والمرشدات',
          self::الافريقي => 'الافريقي',
          self::الهلال_الاحمر => 'الهلال الاحمر',
          self::حغاظ_وأئمة => 'حفاظ و أئمة وقيمين',
      };
  }
}
