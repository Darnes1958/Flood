<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StreetResource\Pages;
use App\Filament\Resources\StreetResource\RelationManagers;
use App\Models\Road;
use App\Models\Street;
use App\Models\Victim;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StreetResource extends Resource
{
    protected static ?string $model = Street::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='أسماء الشوارع';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              TextInput::make('StrName')
                ->required()
                ->label('اسم الشارع')
                ->maxLength(255),
              Select::make('area_id')
                ->relationship('Area','AreaName')
                ->label('المحلة')
                ->searchable()
                ->preload()
                ->reactive()
                ->required(),
              Select::make('road_id')
                    ->searchable()
                    ->relationship('Road','name')
                    ->preload(),
                Forms\Components\Toggle::make('building')
                    ->label('عمارة')
                    ->onColor( 'success')
                    ->offColor( 'gray'),
                Forms\Components\FileUpload::make('image')
                    ->multiple()
                    ->imageEditor()
                    ->directory('Building'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              Tables\Columns\TextColumn::make('StrName')
                ->searchable()
                ->label('اسم الشارع'),

              Tables\Columns\TextColumn::make('Area.AreaName')
                ->label('المحلة'),
              Tables\Columns\SelectColumn::make('road_id')
                ->options(Road::all()->pluck('name','id'))
                  ->searchable()
                    ->label('الشارع الرئيسي'),
              Tables\Columns\ToggleColumn::make('building')
                    ->label('عمارة') ,
                Tables\Columns\ImageColumn::make('image'),

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
            'index' => Pages\ListStreets::route('/'),
            'create' => Pages\CreateStreet::route('/create'),
            'edit' => Pages\EditStreet::route('/{record}/edit'),
        ];
    }
}
