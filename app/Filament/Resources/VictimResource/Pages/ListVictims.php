<?php

namespace App\Filament\Resources\VictimResource\Pages;

use App\Filament\Resources\VictimResource;
use App\Models\Victim;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimResource::class;


    protected function getHeaderActions(): array
    {
        return [
          Actions\Action::make('sendwhatsss')
              ->visible(false)
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
              ->visible(false)
          ->label('ادحال يالعائلات')
          ->icon('heroicon-m-users')
            ->color('success')
          ->url('victims/createbyfather'),


            Actions\Action::make('masterKey')
                ->visible(function (){return Auth::id()==1;})
                ->icon('heroicon-m-users')
                ->color('success')
                ->action(function (){
                    Victim::query()->update(['masterKey'=>null]);
                    $index=Victim::max('masterKey');
                    if (!$index) $index=0;

                    $victims=Victim::where('is_great_grandfather',1)->orderBy('familyshow_id')->orderBy('year')->get();
                    foreach ($victims as $victim){$victim->masterKey=++$index;$victim->save();}

                    $great_grand=Victim::where('is_great_grandfather',1)->pluck('id');
                    $victims=Victim::whereIn('father_id',$great_grand)->orderBy('familyshow_id')->orderBy('year')->get();
                    foreach ($victims as $victim) {
                        if (!$victim->masterKey){$victim->masterKey=++$index;$victim->save();}
                        if ($victim->is_father==1){
                            $victim2s=Victim::where('father_id',$victim->id)->get();
                            foreach ($victim2s as $victim2){
                                $victim2->masterKey=++$index;$victim2->save();
                                if ($victim2->is_father==1){
                                    $victim3s=Victim::where('father_id',$victim2->id)->get();
                                    foreach ($victim3s as $victim3){
                                        $victim3->masterKey=++$index;$victim3->save();
                                    }
                                }
                                }
                            }
                        }


                    $victims=Victim::where('is_grandfather',1)->where('masterKey',null)->orderBy('familyshow_id')->orderBy('year')->get();
                    foreach ($victims as $victim){
                        $victim->masterKey=++$index;$victim->save();
                        if ($victim->is_father==1){
                            $victim2s=Victim::where('father_id',$victim->id)->orderBy('year')->get();
                            foreach ($victim2s as $victim2){
                                $victim2->masterKey=++$index;$victim2->save();
                                    if ($victim2->is_father==1){
                                        $victim3s=Victim::where('father_id',$victim2->id)->orderBy('year')->get();
                                        foreach ($victim3s as $victim3){$victim3->masterKey=++$index;$victim3->save();}
                                        }
                                    }

                                }
                            }



                    $victims=Victim::where('is_father',1)->where('masterKey',null)->orderBy('familyshow_id')->orderBy('year')->get();
                    foreach ($victims as $victim) {
                        $victim->masterKey = ++$index;
                        $victim->save();
                        $victim2s = Victim::where('father_id', $victim->id)->orderBy('year')->get();
                        foreach ($victim2s as $victim2) {$victim2->masterKey = ++$index;$victim2->save();}
                    }

                    $victims=Victim::where('masterKey',null)->orderBy('familyshow_id')
                        ->orderBy('Name2')->orderBy('Name3')->orderBy('Name4')->orderBy('year')->get();
                    foreach ($victims as $victim) {
                        $victim->masterKey = ++$index;
                        $victim->save();
                    }




                    Notification::make()->title('Ok')->send();
                }),


        ];
    }
}
