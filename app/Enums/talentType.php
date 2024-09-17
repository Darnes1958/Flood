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


public function getColor(): string|array|null
{
    return match ($this) {
        self::دارنس => 'primary',
        self::الافريقي => 'success',
        self::الهلال_الاحمر => 'danger',
        self::الكشافة => 'Fuchsia',
        self::مواهب => 'blue',



    };
}


    public function getLabel(): ?string
  {
    return $this->name;
  }
}
