<?php

namespace App\Livewire;

use App\Models\Bedon;
use App\Models\Mafkoden;
use App\Models\Tasreeh;
use App\Models\Who;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class WhoWidget extends BaseWidget
{
  protected int | string | array $columnSpan=2;
  public function getTableRecordKey(Model $record): string
  {
    return uniqid();
  }



  public function table(Table $table): Table
    {
        return $table
           ->defaultSort('name')
            ->query(function (Who $who) {
              $who=Who::query();
              return $who;
            }

            )
            ->striped()
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('who')
                 ->sortable()
                 ->searchable()
                 ->color('info')

                 ->label('المبلغ'),
              Tables\Columns\TextColumn::make('name')
                ->label('الاسم'),
              Tables\Columns\TextColumn::make('FullName')
                ->label('اسم المنظومة'),
              Tables\Columns\TextColumn::make('tel')
                  ->searchable(isIndividual: true, isGlobal: false)
                ->label('هاتف'),
              Tables\Columns\TextColumn::make('ship')
                ->label('القرابة'),
              Tables\Columns\TextColumn::make('mother')
                  ->searchable(isIndividual: true, isGlobal: false)
                    ->label('الأم'),

            ]);
    }
}
