<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Login extends \Filament\Pages\Auth\Login
{
  public function form(Form $form): Form
  {
    return $form
      ->schema([

        $this->getEmailFormComponent(),
        $this->getPasswordFormComponent(),

      ]);
  }
}
