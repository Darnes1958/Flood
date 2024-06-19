<?php

namespace App\Filament\Widgets;

use App\Models\BigFamily;
use App\Models\Family;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class BigFamWidget extends BaseWidget
{
  protected int | string | array $columnSpan=1;
  protected static ?int $sort=2;

  public $tarkeba_id=null;

  #[On('take_tarkeba')]
  public function take_tarkeba($tarkeba_id){
    $this->tarkeba_id=$tarkeba_id;
  }
  public function table(Table $table): Table
  {
    return $table
      ->query(function (BigFamily $tribe) {
        $tribe=BigFamily::query()
        ->when($this->tarkeba_id,function ($q){
          $q->where('tarkeba_id',$this->tarkeba_id);
        })
          ->when(!$this->tarkeba_id,function ($q){
            $q->where('tarkeba_id',1);
          });
        return $tribe;
      }
      )
      ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب العائلات الكبري (القبائل)</div>'))
      ->defaultPaginationPageOption(5)
      ->defaultSort('victim_count','desc')
      ->striped()
      ->columns([
        TextColumn::make('name')
          ->sortable()
          ->action(function (BigFamily $record){
            $this->dispatch('take_big_family',big_family_id: $record->id);
          })
          ->color('blue')
          ->searchable()
          ->label('العائلة'),
        TextColumn::make('victim_count')
          ->color('warning')
          ->sortable()
          ->label('العدد')
          ->counts('Victim'),


      ]);
  }
}
