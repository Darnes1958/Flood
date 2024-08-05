<?php

namespace App\Filament\Resources\VictimResource\Pages;

use App\Filament\Resources\VictimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimResource::class;


    protected function getHeaderActions(): array
    {
        return [
          Actions\Action::make('sendwhatsss')
           ->action(function (){
             $phone='218925518783';
             $curl = curl_init();
             curl_setopt_array($curl, array(
               CURLOPT_URL => 'https://graph.facebook.com/v20.0/407477329105710/messages',
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => '',
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => 'POST',
               CURLOPT_POSTFIELDS =>'{
    "messaging_product": "whatsapp",
    "to": '. $phone.',
    "type": "template",
    "template": {
        "name": "hello_world",
        "language": {
            "code": "en_US"
        }
    }
}',
               CURLOPT_HTTPHEADER => array(
                 'Authorization: Bearer EAAMRnhv4tnUBO5dTgJkdrGT4uAxPnsLhYHl4TH2iTfhlsIxvx3dhm2poS936EuqAEZADsnOdOZAAOK6uHlDjublUOZBgGLN68i4DgP37BVMQEUIuekzByVLOnxBAXdN57CIrzvevlodaGp147lY2XDhDvWI1OyDnOdqE8AYK8NRLC1HqeFnyhzxqPDZBkA1hbJcS2HoMAeySEEw9wQZDZD',
                 'Content-Type: application/json'
               ),
             ));

             $response = curl_exec($curl);

             curl_close($curl);

             echo $response;
           }),
            Actions\CreateAction::make()
            ->label('إضافة ضحية جديدة'),
            Actions\Action::make('ModifyVictim')
                ->label('تعديلات')
                ->icon('heroicon-m-users')
                ->color('danger')
                ->url('victims/modifyvictim'),
          Actions\Action::make('byfammily')
          ->label('ادحال يالعائلات')
          ->icon('heroicon-m-users')
            ->color('success')
          ->url('victims/createbyfather'),

        ];
    }
}
