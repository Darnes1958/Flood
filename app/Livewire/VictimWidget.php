<?php

namespace App\Livewire;

use App\Models\Balag;
use App\Models\Bedon;
use App\Models\Dead;
use App\Models\Mafkoden;
use App\Models\Tasreeh;
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
  public $who;
  public $show_other=true;

  #[On('updatefamily')]
  public function updatefamily($family_id,$with_victim,$show_description,$who,$show_other)
  {
    $this->family_id=$family_id;
    $this->with_victim=$with_victim;
    $this->show_description=$show_description;
    $this->who=$who;
    $this->show_other=$show_other;


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
              ->when(!$this->with_victim && $this->who=='maf',function ($q){
                $q->where('mafkoden',null);
              })
              ->when(!$this->with_victim && $this->who=='tas',function ($q){
                    $q->where('tasreeh',null);
                })
              ->when(!$this->with_victim && $this->who=='bed',function ($q){
                    $q->where('bedon',null);
                })
                ->when(!$this->with_victim && $this->who=='dead',function ($q){
                    $q->where('dead',null);
                })
                ->when(!$this->with_victim && $this->who=='bal',function ($q){
                    $q->where('balag',null);
                })

                ->when(!$this->show_other && $this->who=='maf',function ($q){
                    $q->where('tasreeh',null)->where('bedon',null);
                })
                ->when(!$this->show_other && $this->who=='tas',function ($q){

                    $q->where('mafkoden',null)->where('bedon',null);
                })
                ->when(!$this->show_other && $this->who=='bed',function ($q){

                    $q->where('mafkoden',null)->where('tasreeh',null);
                })
                ->when(!$this->show_other && $this->who=='dead',function ($q){

                    $q->where('mafkoden',null)->where('dead',null);
                })
                ->when(!$this->show_other && $this->who=='bal',function ($q){

                    $q->where('mafkoden',null)->where('dead',null);
                })


            ;

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
                    if ($this->who=='maf') {
                        Mafkoden::find($this->mod_id)->update(['victim_id'=>$record->id]);
                        $record->update(['mafkoden'=>$this->mod_id]);
                    }
                    if ($this->who=='tas') {
                        Tasreeh::find($this->mod_id)->update(['victim_id'=>$record->id]);
                        $record->update(['tasreeh'=>$this->mod_id]);
                    }

                    if ($this->who=='bed') {
                        Bedon::find($this->mod_id)->update(['victim_id'=>$record->id]);
                        $record->update(['bedon'=>$this->mod_id]);
                    }
                    if ($this->who=='dead') {
                        Dead::find($this->mod_id)->update(['victim_id'=>$record->id]);
                        $record->update(['dead'=>$this->mod_id]);
                    }
                  if ($this->who=='bal') {
                    Balag::find($this->mod_id)->update(['victim_id'=>$record->id]);
                    $record->update(['balag'=>$this->mod_id]);
                  }

                  $this->dispatch('reset_mod');

                })
                ->label('الاسم بالكامل')
                ->searchable(),

              Tables\Columns\IconColumn::make('mafkoden')
                  ->state(function (Victim $record){
                      return $record->mafkoden!=null;
                  })
                  ->visible( function (){
                      return $this->who!='maf' && $this->show_other==true;
                  })
                  ->boolean()
                  ->label('مفقود'),
                Tables\Columns\IconColumn::make('tasreeh')
                    ->state(function (Victim $record){
                        return $record->tasreeh!=null;
                    })
                    ->visible( function (){
                        return $this->who!='tas' && $this->show_other==true;
                    })
                    ->boolean()
                    ->label('بتصريح'),
                Tables\Columns\IconColumn::make('bedon')
                    ->state(function (Victim $record){
                        return $record->bedon!=null;
                    })
                    ->visible( function (){
                        return $this->who !='bed' && $this->show_other==true;
                    })
                    ->boolean()
                    ->label('بدون تصريح'),
                Tables\Columns\IconColumn::make('dead')
                    ->state(function (Victim $record){
                        return $record->dead!=null;
                    })
                    ->visible( function (){
                        return $this->who !='dead' && $this->show_other==true;
                    })
                    ->boolean()
                    ->label('متوفيين'),
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
