<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaitResource\Pages;
use App\Filament\Resources\BaitResource\RelationManagers;
use App\Models\Bait;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BaitResource extends Resource
{
    protected static ?string $model = Bait::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              TextInput::make('name')
                ->required()
                ->label('الاسم')
                ->maxLength(255)
                ->required(),
              Select::make('family_id')
                ->label('العائلة')
                ->options(Family::all()->pluck('FamName','id'))
                ->preload()
                ->required()
                ->live()
                ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                  ->searchable()
                ->label('الاسم'),
              Tables\Columns\TextColumn::make('Family.FamName')
                ->searchable()
                ->label('العائلة'),

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
            'index' => Pages\ListBaits::route('/'),
            'create' => Pages\CreateBait::route('/create'),
            'edit' => Pages\EditBait::route('/{record}/edit'),
        ];
    }
}
