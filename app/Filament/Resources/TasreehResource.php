<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TasreehResource\Pages;
use App\Filament\Resources\TasreehResource\RelationManagers;
use App\Models\Tasreeh;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TasreehResource extends Resource
{
    protected static ?string $model = Tasreeh::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel='بتصريح';
    public static function getNavigationBadge(): ?string
    {
        if (Auth::user()->id==1)
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
                    ->state(function (Tasreeh $record): string {
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
            'index' => Pages\ListTasreehs::route('/'),
            'create' => Pages\CreateTasreeh::route('/create'),
            'edit' => Pages\EditTasreeh::route('/{record}/edit'),
            'modifytasreeh' => Pages\ModifyTasreeh::route('/modifytasreeh'),
            'comparetasreeh' => Pages\CompareTas::route('/comparetasreeh'),
        ];
    }
}
