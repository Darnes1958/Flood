<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum Mother: int implements HasLabel,HasColor,HasIcon
{
  case لا = 0;
  case نعم = 1;


  public function getLabel(): ?string
  {
    return $this->name;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::قبض => 'success',
      self::دفع => 'danger',
    };
  }
  public function getIcon(): ?string
  {
    return match ($this) {
      self::قبض => 'heroicon-m-check-circle',
      self::دفع => 'heroicon-m-plus-circle',
    };
  }
}


