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

class VictimWidget extends BaseWidget
{
  public $family_id;
  public $mod_id;
  public $with_victim;
  public $show_description=false;

  #[On('updatefamily')]
  public function updatefamily($family_id,$with_victim,$show_description)
  {
    $this->family_id=$family_id;
    $this->with_victim=$with_victim;
    $this->show_description=$show_description;

  }

  #[On('take_mod_id')]
  public function take_mod_id($mod_id)
  {
    $this->mod_id=$mod_id;
  }
  #[On('reset_vic')]
  public function reset_vic(){
    $this->resetTable();
  }

  public function table(Table $table): Table
    {
        return $table
          ->query(function (Victim $victim) {
            $victim = Victim::where('family_id',$this->family_id)
              ->when(!$this->with_victim,function ($q){
                $q->where('mafkoden',null);
              });
            return $victim;
          })
          ->heading(new HtmlString('<div class="text-primary-400 text-lg">بيانات المنظومة</div>'))
            ->defaultSort('FullName')
            ->columns([
              TextColumn::make('FullName')
                ->description(function (Victim $record){
                  if ($this->show_description){
                    $father='';
                    if ($record->father_id)  $father=$record->sonOfFather->FullName;
                    if ($record->mother_id)  $father =$father.'/'.$record->sonOfMother->FullName;
                      return $father;}
                })
                ->action(function (Victim $record){
                  Mafkoden::find($this->mod_id)->update(['victim_id'=>$record->id]);
                  $record->update(['mafkoden'=>$this->mod_id]);
                  $this->dispatch('reset_mod');

                })
                ->label('الاسم بالكامل')
                ->searchable(),



              TextColumn::make('wife.FullName')
                ->state(function (Victim $record) : string {
                  if ($record->husband_id ) {
                    return $record->wife->FullName;}
                  if ($record->husband_id==null && $record->wife_id ) {
                    return 'الزوجة : ';
                  }
                  if (!$record->husband_id && !$record->wife_id ) {
                    return ' ';
                  }

                })
                ->description(function (Victim $record) {
                  if ($record->wife_id) return $record->husband->FullName;
                })
                ->sortable()
                ->toggleable()
                ->label('الزوج'),

            ]);
    }
}
