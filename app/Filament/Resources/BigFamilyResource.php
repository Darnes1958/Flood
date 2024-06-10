<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BigFamilyResource\Pages;
use App\Filament\Resources\BigFamilyResource\RelationManagers;
use App\Models\BigFamily;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BigFamilyResource extends Resource
{
    protected static ?string $model = BigFamily::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='عائلات كبري';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('اسم العائلة'),
                Forms\Components\Select::make('tarkeba_id')
                    ->searchable()
                    ->preload()
                    ->relationship('Tarkeba','name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('اسم التركيبة الاجتماعية')
                            ->maxLength(255)
                            ->required(),
                    ])
                    ->editOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('اسم التركيبة الاجتماعية ')
                            ->maxLength(255)
                            ->required(),
                    ])
                    ->label('التركيبة الاجتماعية'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('id'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('الاسم'),
                Tables\Columns\TextColumn::make('Tarkeba.name')
                    ->label('التركيبة الاجتماعية'),
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
            'index' => Pages\ListBigFamilies::route('/'),
            'create' => Pages\CreateBigFamily::route('/create'),
            'edit' => Pages\EditBigFamily::route('/{record}/edit'),
        ];
    }
}
