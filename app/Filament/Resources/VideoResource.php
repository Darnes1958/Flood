<?php

namespace App\Filament\Resources;

use App\Enums\Subjects;
use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Doctrine\Inflector\Rules\Substitution;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

              Forms\Components\Select::make('subject')
               ->options(Subjects::class)
               ->label('التصنيف'),
              Forms\Components\TextInput::make('title')
                ->label('العنوان'),
              Forms\Components\TextInput::make('description')
                ->label('الشرح'),
              Forms\Components\FileUpload::make('attachment')
                ->required()
                ->preserveFilenames()
                ->maxSize(20000),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              Tables\Columns\TextColumn::make('subject')
              ->formatStateUsing(fn (Video $record) => Subjects::from($record->subject)->getLabel())

                ->badge()
               ->label('التصنيف'),
              Tables\Columns\TextColumn::make('title')
                ->description(function (Video $record){
                  return $record->description;
                })
                ->label('البيان'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'view' => Pages\ViewVideo::route('/{record}'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
