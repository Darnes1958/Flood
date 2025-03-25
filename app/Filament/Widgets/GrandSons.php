<?php

namespace App\Filament\Widgets;

use App\Models\Family;
use App\Models\Grand_count;
use App\Models\Great_count;
use App\Models\Tarkeba;
use App\Models\Tribe;
use App\Models\Victim;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class GrandSons extends BaseWidget
{
  protected int | string | array $columnSpan = 3;
  protected static ?int $sort=1;

    public $grand=-1;
    public $fathers=[];
    #[On('take_grand')]
    public function take_grand($grand){
        $this->grand=$grand;
        $this->fathers=Victim::query()

            ->where(function ($query) {
                $query->where('father_id',$this->grand)
                    ->orwhere('mother_id',$this->grand);
            })
            ->where(function ($query) {
                $query->where('is_father',1)
                    ->orwhere('is_mother',1);
            })->pluck('id');
    }
  public function table(Table $table): Table
  {
    return $table
      ->query(function () {
          $data=Victim::where(function ($query) {
              $query-> whereIn('father_id',$this->fathers)->orwhereIn('mother_id',$this->fathers);
          })->where(function ($query) {
              $query->where('is_father',1)
                  ->orwhere('is_mother',1);
          })  ;
        return $data;
      }
      )
      ->queryStringIdentifier('grand')
      ->heading(new HtmlString('<div class="text-primary-400 text-lg">الأبناء وأحفادهم</div>'))
      ->striped()
      ->columns([
        TextColumn::make('FullName')

            ->formatStateUsing(fn (Victim $record): View => view(
                'filament.user.pages.full-data',
                ['record' => $record],
            ))
          ->searchable()
          ->label('الإسم '),

      ]);
  }
}
