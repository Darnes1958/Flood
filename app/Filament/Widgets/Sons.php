<?php

namespace App\Filament\Widgets;

use App\Models\Family;
use App\Models\Grand_count;
use App\Models\Great_count;
use App\Models\Tarkeba;
use App\Models\Tribe;
use App\Models\Victim;
use Filament\Actions\StaticAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class Sons extends BaseWidget
{
  protected int | string | array $columnSpan = 3;
  protected static ?int $sort=1;

    public $grand=-1;

    #[On('take_grand')]
    public function take_grand($grand){
        $this->grand=$grand;


    }
  public function table(Table $table): Table
  {
    return $table
      ->query(function () {
          $data=Victim::where('father_id',$this->grand)->orwhere('mother_id',$this->grand)
          ->orwhere('grandfather_id',$this->grand)->orwhere('grandmother_id',$this->grand);

        return $data;
      }
      )
      ->queryStringIdentifier('grand')
      ->heading(new HtmlString('<div class="text-primary-400 text-lg">الأبناء</div>'))
      ->striped()
      ->columns([
        TextColumn::make('FullName')
            ->formatStateUsing(fn (Victim $record): View => view(
                'filament.user.pages.full-data',
                ['record' => $record],
            ))
          ->searchable()
          ->label('الإسم '),
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

              ->circular()
              ->height(100)
              ->limit(1)

      ]);
  }
}
