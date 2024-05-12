<?php

namespace App\Filament\User\Resources\VictimResource\Pages;

use App\Filament\User\Resources\VictimResource;
use App\Models\VicTalent;
use App\Models\Victim;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;

class ViewVictim extends ViewRecord
{
    protected static string $resource = VictimResource::class;
    protected ?string $heading='';
    public $n=0;

  public function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Section::make()
         ->schema([
          TextEntry::make('FullName')
           ->color(function (Victim $record){
             if ($record->male=='ذكر') return 'primary';  else return 'Fuchsia';})
           ->columnSpanFull()
           ->weight(FontWeight::ExtraBold)
           ->size(TextEntry\TextEntrySize::Large)
           ->label(''),
           TextEntry::make('sonOfFather.FullName')
             ->visible(function (Victim $record){
               return $record->father_id;
             })
             ->color('info')
             ->label('والده')
             ->size(TextEntry\TextEntrySize::Large)

             ->columnSpanFull(),
           TextEntry::make('sonOfMother.FullName')
             ->visible(function (Victim $record){
               return $record->mother_id;
             })
             ->color('Fuchsia')
             ->label('والدته')
             ->size(TextEntry\TextEntrySize::Large)

             ->columnSpanFull(),

          TextEntry::make('husband.FullName')
            ->visible(function (Victim $record){
               return $record->wife_id;
             })
            ->color('Fuchsia')
            ->label('زوجته')
            ->size(TextEntry\TextEntrySize::Large)
            ->separator(',')
            ->columnSpanFull(),
           TextEntry::make('husband2.FullName')
             ->visible(function (Victim $record){
               return $record->wife2_id;
             })
             ->color('Fuchsia')
             ->label('زوجته الثانية')
             ->size(TextEntry\TextEntrySize::Large)
             ->columnSpanFull(),
           TextEntry::make('wife.FullName')
             ->visible(function (Victim $record){
               return $record->husband_id;
             })
             ->label('زوجها')
             ->badge()
             ->separator(',')
             ->columnSpanFull(),

          TextEntry::make('father.Name1')
            ->visible(function (Victim $record){
              return $record->is_father;
            })
            ->label('أبناءه')
            ->color(function(){
              $this->n++;
              switch ($this->n){
                case 1: $c='success';break;
                case 2: $c='info';break;
                case 3: $c='yellow';break;
                case 4: $c='rose';break;
                case 5: $c='blue';break;
                case 6: $c='Fuchsia';break;
                default: $c='primary';break;
              }
              return $c;

            })
            ->badge()
            ->separator(',')
            ->columnSpanFull(),
           TextEntry::make('mother.Name1')
             ->visible(function (Victim $record){
               return $record->is_mother;
             })
             ->label('أبناءها')
             ->badge()
             ->separator(',')
             ->columnSpanFull(),

          TextEntry::make('Family.FamName')
            ->color('info')
            ->label('العائلة'),
          TextEntry::make('Family.Tribe.TriName')
            ->color('info')
            ->label('القبيلة'),
          TextEntry::make('Street.StrName')
            ->color('info')
            ->label('العنوان'),
           TextEntry::make('Street.Area.AreaName')
             ->color('info')
             ->label('المحلة'),

           TextEntry::make('Qualification.name')
             ->visible(function (Model $record){
               return $record->qualification_id;
             })
             ->color('info')
             ->label('المؤهل'),
           TextEntry::make('Job.name')
             ->visible(function (Model $record){
               return $record->job_id;
             })
             ->color('info')
             ->label('الوظيفة'),
           TextEntry::make('VicTalent.Talent.name')
             ->visible(function (Model $record){
               return VicTalent::where('victim_id',$record->id)->exists() ;
             })

             ->color('info')
             ->label('المواهب'),
           TextEntry::make('notes')
            ->label('')

         ])
         ->columns(2)
         ->columnSpan(2),

       ImageEntry::make('image')
           ->label('')
           ->size('10')
        ->columnSpan(2)


      ])->columns(4);
  }
}
