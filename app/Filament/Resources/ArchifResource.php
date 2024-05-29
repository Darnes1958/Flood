<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchifResource\Pages;
use App\Filament\Resources\ArchifResource\RelationManagers;
use App\Models\Archif;
use App\Models\Bedon;
use App\Models\Mafkoden;
use App\Models\Tasreeh;
use App\Models\Victim;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ArchifResource extends Resource
{
    protected static ?string $model = Archif::class;

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
          ->striped()
          ->defaultSort('id','desc')
            ->columns([
              Tables\Columns\TextColumn::make('id'),
              Tables\Columns\TextColumn::make('FullName')
                ->label('الاسم بالكامل')
                ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
                ->searchable(),
              Tables\Columns\TextColumn::make('Street.StrName')
                ->label('الشارع'),
              Tables\Columns\TextColumn::make('Family.FamName')
                ->sortable()
                ->label('العائلة'),
              Tables\Columns\TextColumn::make('fromwho')
                ->sortable()
                ->label('بواسطة'),
              Tables\Columns\TextColumn::make('notes')
                ->sortable()
                ->label('ملاحظات'),
            ])
            ->filters([
              Tables\Filters\SelectFilter::make('فلترة بالعائلة')
                ->searchable()
                ->preload()
                ->relationship('Family','FamName'),

              Tables\Filters\SelectFilter::make('فلترة بالشارع')
                ->searchable()
                ->preload()
                ->relationship('Street','StrName'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('toVictim')
                    ->iconButton()
                    ->visible(Auth::user()->can('delete victim'))
                    ->icon('heroicon-s-archive-box')
                    ->requiresConfirmation()
                    ->modalHeading('إرجاع لملف الضحايا')
                    ->modalDescription('هل انت متأكد من إرجاعه ؟')
                    ->fillForm(fn (Archif $record): array => [
                        'notes' => $record->notes,
                    ])
                    ->form([
                        TextInput::make('notes')
                            ->label('ملاحظات')
                    ])
                    ->action(function (Archif $record,array $data){
                        Mafkoden::where('victim_id',$record->id)->update(['victim_id'=>null]);
                        Bedon::where('victim_id',$record->id)->update(['victim_id'=>null]);
                        Tasreeh::where('victim_id',$record->id)->update(['victim_id'=>null]);
                        $victim=Archif::find($record->id);
                        $victim->notes=$data['notes'];
                        Victim::create(collect($victim)->except(['id'])->toArray());
                        $record->delete();
                    }),
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
            'index' => Pages\ListArchifs::route('/'),
            'create' => Pages\CreateArchif::route('/create'),
            'edit' => Pages\EditArchif::route('/{record}/edit'),
        ];
    }
}
