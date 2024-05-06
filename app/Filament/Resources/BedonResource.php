<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BedonResource\Pages;
use App\Filament\Resources\BedonResource\RelationManagers;
use App\Models\Bedon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BedonResource extends Resource
{
    protected static ?string $model = Bedon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
              ->state(function (Bedon $record): string {
                if ($record->sex==1) return 'ذكر';
                if ($record->sex==2) return 'أنثي';
              })
              ->label('الجنس')
            ,

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
            'index' => Pages\ListBedons::route('/'),
            'create' => Pages\CreateBedon::route('/create'),
            'edit' => Pages\EditBedon::route('/{record}/edit'),
          'modifybedon' => Pages\ModifyBedon::route('/modifybedon')

        ];
    }
}
