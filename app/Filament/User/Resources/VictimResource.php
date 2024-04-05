<?php

namespace App\Filament\User\Resources;

use App\Filament\Exports\ByFamilyExporter;
use App\Filament\User\Resources\VictimResource\Pages;
use App\Filament\User\Resources\VictimResource\RelationManagers;
use App\Models\Victim;
use Filament\Actions\Action;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VictimResource extends Resource
{
    protected static ?string $model = Victim::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


  protected static ?string $navigationLabel='كشف تفصيلي بالضحايا';

  public $family_id;
  public $filters;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

          ->striped()
          ->defaultPaginationPageOption(10)
          ->paginated([10, 25, 50, 100,])
          ->columns([
            TextColumn::make('FullName')
              ->label('الاسم بالكامل')
              ->sortable()
              ->description(function(Victim $record) {
                if ($record->is_father) {
                  $Arr = 'وأبناءه : ';
                  foreach ($record->father as $v) if ($Arr == 'وأبناءه : ') $Arr = $Arr . $v->Name1; else $Arr = $Arr . ',' . $v->Name1;
                  return ($Arr);
                }
                if ($record->wife_id && !$record->is_father)   return 'زوج : '.$record->husband->FullName;


                if ($record->is_mother && !$record->husband_id) {
                  $Arr = 'وأبناءها : ';
                  foreach ($record->mother as $v) if ($Arr == 'وأبناءها : ') $Arr = $Arr . $v->Name1; else $Arr = $Arr . ',' . $v->Name1;
                  return ($Arr);
                }

                if ($record->husband_id)  return 'زوجة : '.$record->wife->FullName;
              }
              )
              ->searchable(),

            TextColumn::make('Family.FamName')
              ->sortable()
              ->searchable()
              ->label('العائلة'),
            TextColumn::make('Street.StrName')
              ->sortable()
              ->searchable()
              ->label('العنوان'),
            TextColumn::make('Qualification.name')
              ->sortable()
              ->searchable()
              ->label('المهنة'),
            TextColumn::make('Job.name')
              ->sortable()
              ->searchable()
              ->label('الوظيفة'),
            Tables\Columns\TextColumn::make('VicTalent.Talent.name')
              ->label('المواهب'),
            ImageColumn::make('image')
              ->label('')
              ->circular(),
          ])

            ->filters([
              SelectFilter::make('family')
                ->label('فلترة بالعائلة')
                ->searchable()
                ->preload()
                ->relationship('Family','FamName'),
              SelectFilter::make('فلترة بالقبيلة')
                ->searchable()
                ->preload()
                ->relationship('Family','Tribe.TriName'),
              SelectFilter::make('فلترة بالشارع')
                ->searchable()
                ->preload()
                ->relationship('Street','StrName'),
              SelectFilter::make('فلترة بالمحلة')
                ->searchable()
                ->preload()
                ->relationship('Street','Area.AreaName'),

            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
          ->filtersTriggerAction(
            fn (Tables\Actions\Action $action) => $action
              ->button()
              ->label('إضفط هنا لفتح واغلاق الفلترة'),
          )
            ->actions([
                Tables\Actions\ViewAction::make(),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVictims::route('/'),

            'view' => Pages\ViewVictim::route('/{record}'),

        ];
    }
}
