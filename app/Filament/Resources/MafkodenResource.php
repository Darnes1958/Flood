<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MafkodenResource\Pages;
use App\Filament\Resources\MafkodenResource\RelationManagers;
use App\Models\Archif;
use App\Models\Family;
use App\Models\Mafkoden;
use App\Models\Victim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MafkodenResource extends Resource
{
    protected static ?string $model = Mafkoden::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel='مفقودين';
    public static function getNavigationBadge(): ?string
    {
      if (Auth::user()->can('show count'))
        return static::getModel()::count();
      else return ' ';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\TextInput::make('name')
                 ->label('الإسم'),
              Forms\Components\Select::make('family_id')
                ->relationship('Family','FamName')
                ->searchable()
                ->preload()
                ->label('الغائلة'),
              Forms\Components\Radio::make('sex')
                ->options([
                  1=>'ذكر',
                  2=>'أنثي',
                ])
                ->label('الجنس'),
              Forms\Components\TextInput::make('nation')
                ->label('الجنسية'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              Tables\Columns\TextColumn::make('nation')
                ->label('الجنسية'),
              Tables\Columns\TextColumn::make('Family.FamName')
                ->searchable()
                ->sortable()
                ->label('العائلة'),
              Tables\Columns\TextColumn::make('id')
                ->label('الرقم الألي'),
              Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable()
                ->label('الاسم'),
              Tables\Columns\TextColumn::make('sex')
                ->state(function (Mafkoden $record): string {
                  if ($record->sex==1) return 'ذكر';
                  if ($record->sex==2) return 'أنثي';
                })


              ->label('الجنس')
                ,
              Tables\Columns\TextColumn::make('birth')
                ->label('مواليد'),
              Tables\Columns\TextColumn::make('mother')
                ->searchable()
                ->label('الام'),
              Tables\Columns\TextColumn::make('who')
                ->searchable()
                ->label('المبلغ'),

              Tables\Columns\TextColumn::make('tel')
                ->label('هاتف المبلغ'),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('moveMaf')
                    ->deselectRecordsAfterCompletion()
                    ->label('نسخ لملف الضحايا')
                    ->action(function (Collection $record){
                        foreach ($record as $item){
                            if ($item->male=='1') $male='ذكر'; else $male='أنثي';
                            Victim::create([
                                'Name1'=>$item->Name1,
                                'Name2'=>$item->Name2,
                                'Name3'=>$item->Name3,
                                'Name4'=>$item->Name4,
                                'FullName'=>$item->name,
                                'family_id'=>$item->family_id,
                                'street_id'=>12,
                                'male'=>$male,
                                'notes'=>$item->notes,
                                'mafkoden'=>$item->id,
                                'fromwho'=>'مفقودين',
                            ]);
                        }

                    }
                    ),
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
            'index' => Pages\ListMafkodens::route('/'),
            'create' => Pages\CreateMafkoden::route('/create'),
            'edit' => Pages\EditMafkoden::route('/{record}/edit'),
            'modifymafkoden' => Pages\ModifyMafkoden::route('/modifymafkoden'),
            'comparemafkoden' => Pages\CompareMaf::route('/comparemafkoden')
        ];
    }
}
