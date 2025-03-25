<?php

namespace App\Filament\Widgets;

use App\Models\Family;
use App\Models\Grand_count;
use App\Models\Great_count;
use App\Models\Tarkeba;
use App\Models\Tribe;
use Filament\Actions\StaticAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class GraetGrandFather extends BaseWidget
{
  protected int | string | array $columnSpan = 2;
  protected static ?int $sort=1;

  public function table(Table $table): Table
  {
    return $table
      ->query(function () {
        $data=Great_count::query();
        return $data;
      }
      )
        ->queryStringIdentifier('tarkeba')
      ->heading(new HtmlString('<div class="text-primary-400 text-lg">أجداد الأب والأم</div>'))


      ->striped()
      ->columns([
        TextColumn::make('FullName')
          ->sortable()
            ->action(function (Great_count $record){
                $this->dispatch('take_grand',grand: $record->id);
            })
          ->color('blue')
          ->searchable()
          ->label('الإسم '),
        TextColumn::make('thesum')
          ->color('warning')
          ->sortable()
          ->label('عدد الأسرة'),
          ImageColumn::make('image2')
              ->label('')
              ->tooltip(function ($record){
                  if ($record->image2 !=null) return 'انقر هنا لعرض الصور بحجم أكبر' ;
                  else return null;})
              ->action(
                  Tables\Actions\Action::make('show_images')
                      ->visible(function ($record){return $record->image2 !=null;})
                      ->label(' ')
                      ->modalSubmitAction(false)
                      ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة'))
                      ->infolist([
                          ImageEntry::make('image2')
                              ->label('')
                              ->height(500)
                              ->stacked()
                      ])
              )
              
              ->limit(1)
              ->circular()

      ]);
  }
}
