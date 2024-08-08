<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VictimResource\Pages;
use App\Filament\Resources\VictimResource\RelationManagers;
use App\Models\Area;
use App\Models\Job;
use App\Models\Qualification;
use App\Models\Street;
use App\Models\Talent;
use App\Models\Tribe;
use App\Models\Victim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Closure;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Radio;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Auth;

class VictimResource extends Resource
{

    protected static ?string $model = Victim::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $pluralModelLabel='ضحايا';

    public static function getNavigationBadge(): ?string
    {
        if (Auth::user()->can('show count'))
            return static::getModel()::count();
        else return ' ';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\Toggle::make('is_father')
                ->onColor(function (Get $get){
                  if ($get('male')=='ذكر') return 'success';
                  else return 'gray';})
                ->offColor(function (Get $get){
                  if ($get('male')=='ذكر') return 'danger';
                  else return 'gray';})
               ->disabled(fn(Get $get): bool=>$get('male')=='أنثي')
               ->label('أب'),
              Forms\Components\Toggle::make('is_mother')
                ->onColor(function (Get $get){
                  if ($get('male')=='أنثي') return 'success';
                  else return 'gray';})
                ->offColor(function (Get $get){
                  if ($get('male')=='أنثي') return 'danger';
                  else return 'gray';})
                ->label('أم'),
              Radio::make('male')
                ->label('الجنس')
                ->inline()
                ->default('ذكر')
                ->columnSpan(2)
                ->reactive()
                ->afterStateUpdated(function(Forms\Set $set,$state) {
                  if ($state=='ذكر')  $set('is_mother',0);
                  else $set('is_father',0);})
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
                Select::make('wife2_id')
                    ->label('زوجة ثانية')
                    ->relationship('wife2','FullName', fn (Builder $query) => $query
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
                  if ($rec) {
                      $set('Name2', $rec->Name1);
                      $set('Name3', $rec->Name2);
                      $set('Name4', $rec->Name4);
                      $set('family_id', $rec->family_id);
                      $set('street_id', $rec->street_id);
                      if ($rec->wife_id) $set('mother_id', $rec->wife_id);
                  }
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

              Select::make('qualification_id')
                ->label('المؤهل')
                ->relationship('Qualification','name')
                ->searchable()
                ->preload()
                ->reactive()
                ->createOptionForm([
                  Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('المؤهل')
                    ->maxLength(255),
                ])
                ->editOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('المؤهل')
                    ->maxLength(255),
                ]),


              Select::make('job_id')
                ->label('الوظيفة')
                ->relationship('Job','name')
                ->searchable()
                ->preload()
                ->reactive()
                ->createOptionForm([
                  Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('الوظيفة')
                    ->maxLength(255),
                ])
                ->editOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('الوظيفة')
                    ->maxLength(255),
                ]),



              TextInput::make('notes')
                ->columnSpan(2)
                ->label('ملاحظات'),
              TextInput::make('otherName')
                ->label('إسم أخر'),
              Forms\Components\FileUpload::make('image')
                ->directory('form-attachments'),
              TextInput::make('FullName')
              ->hidden(),
              TextInput::make('user_id')
                ->hidden(),
              TextInput::make('fromwho')
                ->hidden(),

            ])->columns(4)
          ;

    }
    public static function table(Table $table): Table
    {
        return $table

            ->striped()
            ->defaultSort('id','desc')
            ->columns([
              Tables\Columns\TextColumn::make('id')
              ->searchable(),
              Tables\Columns\TextColumn::make('FullName')
              ->label('الاسم بالكامل')
              ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
              ->searchable(),
              Tables\Columns\TextColumn::make('Street.StrName')
                ->label('الشارع'),

              Tables\Columns\TextColumn::make('Family.FamName')
                ->sortable()

                ->label('العائلة'),
                Tables\Columns\SelectColumn::make('male')
                    ->options([
                        'ذكر' => 'ذكر',
                        'أنثي' => 'أنثي',
                    ]),

                Tables\Columns\SelectColumn::make('qualification_id')
                    ->label('المهنة')
                    ->options(Qualification::all()->pluck('name','id')),
                Tables\Columns\SelectColumn::make('job_id')
                    ->label('الوظيفة')
                    ->options(Job::all()->pluck('name','id')),
                Tables\Columns\TextColumn::make('VicTalent.Talent.name')
                    ->label('المواهب'),

              Tables\Columns\ImageColumn::make('image')
                ->circular(),


            ])
          ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
            Tables\Actions\Action::make('talent')
              ->label('مواهب')
              ->icon('heroicon-m-plus')
              ->color('success')
              ->url(fn(Model $record) => self::getUrl('createtalent', ['record' => $record])),

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
            'createbyfather' => Pages\CreateByFather::route('/createbyfather'),
            'create' => Pages\CreateVictim::route('/create'),
            'edit' => Pages\EditVictim::route('/{record}/edit'),
            'createtalent' => Pages\CreateTalent::route('/{record}/createtalent'),
            'modifyvictim' =>Pages\Modifies::route('/modifyvictim')

        ];
    }
}
