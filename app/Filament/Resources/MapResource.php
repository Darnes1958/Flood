<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MapResource\Pages;
use App\Filament\Resources\MapResource\RelationManagers;
use App\Models\Map;
use App\Models\Sell;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MapResource extends Resource
{
    protected static ?string $model = Map::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('location'),
                Forms\Components\TextInput::make('location2'),
                Forms\Components\TextInput::make('lng'),
                Forms\Components\TextInput::make('ltd'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location'),
              Tables\Columns\TextColumn::make('location2'),
              Tables\Columns\TextColumn::make('lng'),
              Tables\Columns\TextColumn::make('ltd'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
              Action::make('عرض')
                ->modalHeading(false)
                ->action(fn (Map $record) => $record->id())
                ->modalSubmitAction(false)
                ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة'))
                ->modalContent(fn (Map $record): View => view(
                  'filament.pages.views.view-map',
                  ['address' => $record->location,],
                ))
                ->icon('heroicon-o-eye')
                ->iconButton(),
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
            'index' => Pages\ListMaps::route('/'),
            'create' => Pages\CreateMap::route('/create'),
            'edit' => Pages\EditMap::route('/{record}/edit'),
        ];
    }
}
