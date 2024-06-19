<?php

namespace App\Filament\User\Pages;

class Dashboarduser extends \Filament\Pages\Dashboard
{
  public function getColumns(): int | string | array
  {
    return 3;
  }
}