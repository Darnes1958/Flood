<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VictimResource\Pages;
use App\Filament\Resources\VictimResource\RelationManagers;
use App\Models\Area;
use App\Models\Street;
use App\Models\Tribe;
use App\Models\Victim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Radio;
use Filament\Forms\Get;

class VictimResource extends Resource
{
    protected static ?string $model = Victim::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $pluralModelLabel='ضحايا';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

              Radio::make('male')
                ->label('الجنس')
                ->inline()
                ->default('ذكر')
                ->reactive()
                ->options([
                  'ذكر' => 'ذكر',
                  'أنثي' => 'أنثى',
                ]),
              Select::make('husband_id')
               ->label('زوجة')
                ->relationship('husband', 'FullName', fn (Builder $query) => $query
                  ->where('male','ذكر'))
               ->searchable()
               ->reactive()
               ->preload()
               ->visible(fn (Get $get) => $get('male') == 'أنثي'),
              Select::make('wife_id')
                ->label('زوج')
                ->relationship('wife','FullName', fn (Builder $query) => $query
                  ->where('male','أنثي'))
                ->searchable()
                ->reactive()
                ->preload()
                ->visible(fn (Get $get) => $get('male') == 'ذكر'),
              Select::make('father_id')
                ->label('والده')
                ->relationship('sonOfFather','FullName', fn (Builder $query) => $query
                  ->where('male','ذكر'))
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                  $rec=Victim::where('id',$state)->first();
                  $set('Name2',$rec->Name1);
                  $set('Name3',$rec->Name2);
                  $set('Name4',$rec->Name4);
                  $set('family_id',$rec->family_id);
                  $set('street_id',$rec->street_id);
                  if ($rec->wife_id) $set('mother_id',$rec->wife_id);

                })
                ->preload(),
              Select::make('mother_id')
                ->label('والدته')
                ->relationship('sonOfMother','FullName', fn (Builder $query) => $query
                  ->where('male','أنثي'))
                ->searchable()
                ->reactive()
                ->preload(),

              TextInput::make('Name1')
                ->label('الإسم الاول')
              ->required(),
              TextInput::make('Name2')
                ->label('الإسم الثاني')
                ->required(),
              TextInput::make('Name3')
                ->label('الإسم الثالث'),
              TextInput::make('Name4')
                ->label('الإسم الرابع'),

              Select::make('family_id')
                  ->label('العائلة')
                  ->relationship('Family','FamName')
                  ->searchable()
                  ->reactive()
                  ->preload()
                  ->createOptionForm([
                    TextInput::make('FamName')
                      ->required()
                      ->label('اسم العائلة')
                      ->maxLength(255),
                    Select::make('tribe_id')
                      ->relationship('Tribe','TriName')
                      ->label('القبيلة')
                      ->searchable()
                      ->preload()
                      ->createOptionForm([
                        TextInput::make('TriName')
                          ->required()
                          ->label('اسم القبيلة')
                          ->maxLength(255)
                          ->required(),
                      ])
                      ->reactive()
                      ->required(),
                  ])
                  ->editOptionForm([
                    TextInput::make('FamName')
                    ->required()
                    ->label('اسم العائلة')
                    ->maxLength(255),
                  Select::make('tribe_id')
                    ->relationship('Tribe','TriName')
                    ->label('القبيلة')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required(),
                ])
                  ->required(),
              Select::make('street_id')
                ->label('الشارع')
                ->relationship('Street','StrName')
                ->searchable()
                ->preload()
                ->reactive()
                ->createOptionForm([
                  Forms\Components\TextInput::make('StrName')
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
                ])
                ->editOptionForm([
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
                ])
                ->required(),
              Forms\Components\FileUpload::make('image')
                ->directory('form-attachments')
              ,
              TextInput::make('FullName')
              ->hidden(),

            ])->columns(4)
          ;

    }
    public static function table(Table $table): Table
    {


        return $table

            ->striped()

            ->columns([

              Tables\Columns\TextColumn::make('FullName')
              ->label('الاسم بالكامل')

              ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
              ->searchable(),
              Tables\Columns\TextColumn::make('Street.StrName')
                ->label('الشارع'),
              Tables\Columns\TextColumn::make('Street.Area.AreaName')
                ->label('المحلة'),
              Tables\Columns\TextColumn::make('Family.FamName')
                ->label('العائلة'),
              Tables\Columns\TextColumn::make('Family.Tribe.TriName')
                ->label('القبيلة'),
              Tables\Columns\ImageColumn::make('image')

                ->circular(),

            ])
          ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
          ])

            ->filters([
                Tables\Filters\SelectFilter::make('فلترة بالعائلة')
                  ->relationship('Family','FamName'),
                Tables\Filters\SelectFilter::make('فلترة بالقبيلة')
                  ->relationship('Family','Tribe.TriName'),
                Tables\Filters\SelectFilter::make('فلترة بالشارع')
                   ->relationship('Street','StrName'),
                Tables\Filters\SelectFilter::make('فلترة بالمحلة')
                  ->relationship('Street','Area.AreaName'),
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
            'index' => Pages\ListVictims::route('/'),
            'create' => Pages\CreateVictim::route('/create'),
            'edit' => Pages\EditVictim::route('/{record}/edit'),
        ];
    }
}
