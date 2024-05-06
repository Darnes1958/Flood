<?php

namespace App\Filament\Resources;

use App\Enums\Marry;
use App\Filament\Resources\HadamResource\Pages;
use App\Filament\Resources\HadamResource\RelationManagers;
use App\Models\Hadam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class HadamResource extends Resource
{
    protected static ?string $model = Hadam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              TextInput::make('no')
               ->label('الرقم'),
              DatePicker::make('date')
                ->label('التاريخ'),
              Select::make('wakeel_id')
                ->relationship('Wakeel','name')
                ->searchable()
                ->preload()
                ->createOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('وكيل النيابة')
                    ->maxLength(255),
                ])
                ->editOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('وكيل النيابة')
                    ->maxLength(255),
                ])
                ->label('وكيل النيابة'),
              TextInput::make('name')
                ->label('الاسم'),
              TextInput::make('mother')
                ->label('اسم الام'),
              DatePicker::make('birth')
                ->label('تاريخ الميلاد'),
              TextInput::make('nat_id')
                ->label('الرقم الوطني'),
              Select::make('h_area_id')
                ->relationship('H_area','name')
                ->createOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('محل الاقامة')
                    ->maxLength(255),
                ])
                ->editOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('محل الاقامة')
                    ->maxLength(255),
                ])
                ->label('محل الاقامة'),
              Select::make('marry')
                ->options(Marry::class)
                ->label('الحالة الاجتماعية'),
              TextInput::make('damages')
                ->label('الاضرار'),
              TextInput::make('place')
                ->label('مكان العقار'),
              Select::make('place_type_id')
                ->relationship('Place_type','name')
                ->label('نوع العفار'),
              TextInput::make('mostand')
                ->label('مستند الملكية'),
              TextInput::make('anyother')
                ->label('ملحقات العقار'),
              TextInput::make('notes')
                ->label('ملاحظات'),
              TextInput::make('location')
                ->label('موقع قوقل'),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('no')
                ->label('الرقم'),
              TextColumn::make('date')
                ->toggleable()
                ->label('التاريخ'),
              TextColumn::make('Wakeel.name')
                ->toggleable()
                ->label('وكيل النيابة'),
              TextColumn::make('name')
                ->description(function (Hadam $record) {
                  if ($record->mother) return $record->mother;
                })
                ->searchable()
                ->sortable()
                ->label('الاسم'),
              TextColumn::make('birth')
                ->toggleable()
                ->label('تاريخ الميلاد'),
              TextColumn::make('nat_id')
                ->toggleable()
                ->label('الرقم الوطني'),
              TextColumn::make('H_area.name')
                ->toggleable()
                ->description(function (Hadam $record) {
                  if ($record->place) return $record->place;
                })
                ->label('محل الاقامة'),
              TextColumn::make('marry')
                ->toggleable()
                ->label('الحالة'),
              TextColumn::make('damages')
                ->lineClamp(2)
                ->toggleable()
                ->label('الاضرار'),
              TextColumn::make('Place_Type.name')
                ->toggleable()
                ->description(function (Hadam $record) {
                  if ($record->anyother) return $record->mostand.' / '.$record->anyother;
                  else return $record->mostand;
                })
                ->label('نوع العقار'),

              TextColumn::make('notes')
                ->toggleable()
                ->label('ملاحظات'),
              TextColumn::make('location')
                ->toggleable()
                ->label('موقع قوقل'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
               //
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
            'index' => Pages\ListHadams::route('/'),
            'create' => Pages\CreateHadam::route('/create'),
            'edit' => Pages\EditHadam::route('/{record}/edit'),
        ];
    }
}
