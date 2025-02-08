<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyshowResource\Pages;
use App\Filament\Resources\FamilyshowResource\RelationManagers;
use App\Models\BigFamily;
use App\Models\Family;
use App\Models\Familyshow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyshowResource extends Resource
{
    protected static ?string $model = Familyshow::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                Tables\Columns\SelectColumn::make('big_family_id')
                    ->options(BigFamily::all()->pluck('name','id'))
                    ->label('القبيلة'),

                Tables\Columns\TextColumn::make('victim_count')
                    ->sortable()
                    ->label('العدد')
                    ->counts('Victim'),

                Tables\Columns\TextColumn::make('who')
                    ->action(
                        Tables\Actions\Action::make('updateBy')
                            ->form([
                                Forms\Components\TextInput::make('who')
                                    ->label('بمعرفة')
                            ])
                            ->fillForm(fn (Familyshow $record): array => [
                                'who' => $record->who,
                            ])
                            ->modalCancelActionLabel('عودة')
                            ->modalSubmitActionLabel('تحزين')
                            ->modalHeading('ادحال وتعديل بمعرفة')
                            ->action(function (array $data,Familyshow $record,){
                                $record->update(['who'=>$data['who']]);
                            })
                    )
                    ->sortable()
                    ->label('بمعرفة'),
                Tables\Columns\TextColumn::make('nation')
                    ->action(
                        Tables\Actions\Action::make('updatenation')
                            ->form([
                                Forms\Components\TextInput::make('nation')
                                    ->label('الجنسية')
                            ])
                            ->fillForm(fn (Familyshow $record): array => [
                                'nation' => $record->nation,
                            ])
                            ->modalCancelActionLabel('عودة')
                            ->modalSubmitActionLabel('تحزين')
                            ->modalHeading('ادحال وتعديل بمعرفة')
                            ->action(function (array $data,Familyshow $record,){
                                $record->update(['nation'=>$data['nation']]);
                            })
                    )
                    ->label('الجنسية'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('فلترة بالقبيلة')
                    ->searchable()
                    ->relationship('Tribe','TriName'),
            ])
            ->actions([
               //
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
            'index' => Pages\ListFamilyshows::route('/'),
            'create' => Pages\CreateFamilyshow::route('/create'),
            'edit' => Pages\EditFamilyshow::route('/{record}/edit'),
        ];
    }
}
