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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class GrandFather extends BaseWidget
{
  protected int | string | array $columnSpan = 2;
  protected static ?int $sort=1;

  public function table(Table $table): Table
  {
    return $table
      ->query(function () {
        $data=Grand_count::query()->orderBy('thesum', 'desc')->where('thesum','>=',20);
        return $data;
      }
      )
      ->queryStringIdentifier('grand')
      ->heading(new HtmlString('<div class="text-primary-400 text-lg">اكبر الأسر</div>'))
      ->description('إضغط علي الإسم من القائمة أدناه لعرض الأبناء والأحفاد')
      ->striped()
      ->columns([
        TextColumn::make('FullName')
          ->sortable()
            ->action(function (Grand_count $record){
                $this->dispatch('take_grand',grand: $record->id);
            })
            ->tooltip('أنقر هنا لعرض التفاصيل')
          ->description(function (Grand_count $record) {
              $res=null;
              if ($record->male='ذكر' && $record->wife_id != null) $res='زوجته : '.Victim::find($record->wife_id)->FullName;
              if ($record->male='أنثي' && $record->husband_id != null) $res='زوجها : '.Victim::find($record->husband_id)->FullName;
              return $res;
          })
          ->color('blue')
          ->searchable()
          ->label('الإسم '),
        TextColumn::make('thesum')


          ->color('warning')
          ->sortable()
          ->label('عدد الأسرة')
      ]);
  }
}
