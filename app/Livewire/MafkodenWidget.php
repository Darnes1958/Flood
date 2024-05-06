<?php

namespace App\Livewire;

use App\Models\Mafkoden;
use App\Models\Victim;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class MafkodenWidget extends BaseWidget
{
  public $family_id;
  public $repeted;

  public $with_victim;
  public $show_description=false;

  #[On('updatefamily')]
  public function updatefamily($family_id,$with_victim,$show_description)
  {
    $this->family_id=$family_id;
    $this->with_victim=$with_victim;
    $this->show_description=$show_description;

  }
  #[On('reset_mod')]
  public function reset_mod(){
    $this->resetTable();
  }
    public function table(Table $table): Table
    {
        return $table
          ->query(function (Mafkoden $mafkoden) {
            $mafkoden = Mafkoden::where('family_id',$this->family_id)
              ->where('nation','ليبيا')

              ->when(!$this->with_victim,function ($q){
                $q->where('victim_id',null)->where('repeted',0);
              })

            ;
            return $mafkoden;
          })
          ->striped()
          ->defaultSort('name')
          ->heading(new HtmlString('<div class="text-primary-400 text-lg">بلاغات المفقودين</div>'))
          ->columns([
            TextColumn::make('name')
              ->description(function (Mafkoden $record){
                if ($this->show_description)
                 if ($record->mother) return $record->mother;
              })
              ->action(function (Mafkoden $record){
                $this->dispatch('take_mod_id', mod_id: $record->id);
              })
              ->label('الاسم بالكامل')
              ->searchable(),
            TextColumn::make('Victim.FullName')
              ->label('الاسم فالمنظومة')
              ->searchable()
              ->action(function (Mafkoden $record){
                Victim::find($record->victim_id)->update(['mafkoden'=>null]);
                $record->update(['victim_id'=>null]);
                $this->dispatch('reset_vic');

              })

              ->sortable(),
            TextColumn::make('birth')
              ->label('مواليد')
              ->sortable(),

            Tables\Columns\IconColumn::make('repeted')
              ->label('مكرر')
              ->action(function (Mafkoden $record): void {
                if ($record->repeted==1) $this->repeted=0; else $this->repeted=1;
                $record->update(['repeted'=>$this->repeted]);
              })

              ->boolean(),
          ])

          ;
    }
}
