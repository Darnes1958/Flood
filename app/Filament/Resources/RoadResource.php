<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoadResource\Pages;
use App\Filament\Resources\RoadResource\RelationManagers;
use App\Models\Area;
use App\Models\Road;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoadResource extends Resource
{
    protected static ?string $model = Road::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='شوارع رئيسية';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                 ->required()
                 ->unique()
                 ->label('الشارع الرئيسي'),
                Select::make('area_id')
                    ->relationship('Area','AreaName')
                    ->label('المحلة')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('الشارع الرئيسي'),
                Tables\Columns\SelectColumn::make('area_id')
                  ->label('المحلة')
                  ->options(Area::all()->pluck('AreaName', 'id')),

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
            'index' => Pages\ListRoads::route('/'),
            'create' => Pages\CreateRoad::route('/create'),
            'edit' => Pages\EditRoad::route('/{record}/edit'),
        ];
    }
}
