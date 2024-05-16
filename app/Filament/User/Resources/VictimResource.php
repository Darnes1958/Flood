<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Pages\Mysearch;
use App\Filament\User\Resources\VictimResource\Pages;
use App\Filament\User\Resources\VictimResource\RelationManagers;
use App\Models\Bedon;
use App\Models\Family;
use App\Models\Mafkoden;
use App\Models\Tasreeh;
use App\Models\VicTalent;
use App\Models\Victim;
use Doctrine\DBAL\Schema\Schema;
use Filament\Actions\EditAction;

use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\ImageEntry;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;


class VictimResource extends Resource
{
    protected static ?string $model = Victim::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  protected static ?string $navigationLabel='كشف تفصيلي بالضحايا';
    protected static ?int $navigationSort=2;

  public $family_name;
  static $family_id;
  public $filters;
  static $ser=0;

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

          ])->columns(4)
           ;
    }

  public static function table(Table $table): Table
  {
    return $table
      ->striped()
      ->defaultPaginationPageOption(10)
      ->paginated([5,10,15, 25, 50, 100,])
      ->toggleColumnsTriggerAction(
        fn (Action $action) => $action
          ->button()
          ->label('إخفاء وإظهار الا‘عمدة'),
      )
      ->columns([
        TextColumn::make('fromwho')
          ->color(function ($state){
            switch ($state){
              case 'المنظومة': $c='info';break;
              case 'مفقودين': $c='rose';break;
              case 'بتصريح': $c='success';break;
              case 'بدون': $c='primary';break;
            }
            return $c;
          })
          ->toggleable()
          ->label('بواسطة'),

        TextColumn::make('FullName')
          ->label('الاسم بالكامل')
          ->state(function (Victim $record){
            if ($record->otherName) return $record->FullName.' ['.$record->otherName.']';
            else return $record->FullName;
          })
          ->sortable()
          ->description(function(Victim $record) {
            if ($record->is_father) {
              $Arr = 'وأبناءه : ';
              foreach ($record->father as $v) if ($Arr == 'وأبناءه : ') $Arr = $Arr . $v->Name1; else $Arr = $Arr . ',' . $v->Name1;
              return ($Arr);
            }
            if ($record->wife_id && !$record->is_father)   return 'زوج : '.$record->husband->FullName;


            if ($record->is_mother && !$record->husband_id) {
              $Arr = 'وأبناءها : ';
              foreach ($record->mother as $v) if ($Arr == 'وأبناءها : ') $Arr = $Arr . $v->Name1; else $Arr = $Arr . ',' . $v->Name1;
              return ($Arr);
            }
            if ($record->husband_id)  return 'زوجة : '.$record->wife->FullName;
          }
          )
          ->searchable(),
        TextColumn::make('Family.FamName')
          ->sortable()
          ->toggleable()
          ->searchable()
          ->label('العائلة'),
        TextColumn::make('Street.StrName')
          ->sortable()
          ->toggleable()
          ->searchable()
          ->label('العنوان'),

        TextColumn::make('mafkoden')
          ->label('مفقودين')
          ->toggleable()
          ->state(function (Victim $record){
            if (!$record->mafkoden) return null;
            return Mafkoden::find($record->mafkoden)->name;
          })
          ->action(
            Action::make('updateMaf')
             ->requiresConfirmation()
             ->modalHeading('هل انت متأكد من تعديل الإسم')

             ->action(function (Victim $record){
               $rec=Mafkoden::find($record->mafkoden);
               $record->update([
                 'FullName'=>$rec->name,'Name1'=>$rec->Name1,'Name2'=>$rec->Name2,'Name3'=>$rec->Name3,'Name4'=>$rec->Name4,
               ]);
             })
          )
          ->description(function (Victim $record){
            if (!$record->mafkoden) return null;
            $rec=Mafkoden::find($record->mafkoden);
            $data=$rec->who;
            if ($rec->tel) $data=$data.'/'.$rec->tel;
            return $data;
          }),
        TextColumn::make('tasreeh')
          ->label('بتصريح')
          ->action(
            Action::make('updateTas')
              ->requiresConfirmation()
              ->modalHeading('هل انت متأكد من تعديل الإسم')
              ->action(function (Victim $record){
                $rec=Tasreeh::find($record->tasreeh);
                $record->update([
                  'FullName'=>$rec->name,'Name1'=>$rec->Name1,'Name2'=>$rec->Name2,'Name3'=>$rec->Name3,'Name4'=>$rec->Name4,
                ]);
              })
          )
          ->toggleable()
          ->state(function (Victim $record){
            if (!$record->tasreeh) return null;
            return Tasreeh::find($record->tasreeh)->name;
          }),

        TextColumn::make('bedon')
          ->label('بدون')
          ->action(
            Action::make('updateBed')
              ->requiresConfirmation()
              ->modalHeading('هل انت متأكد من تعديل الإسم')
              ->action(function (Victim $record){
                $rec=Bedon::find($record->bedon);
                $record->update([
                  'FullName'=>$rec->name,'Name1'=>$rec->Name1,'Name2'=>$rec->Name2,'Name3'=>$rec->Name3,'Name4'=>$rec->Name4,
                ]);
              })
          )
          ->toggleable()
          ->state(function (Victim $record){
            if (!$record->bedon) return null;
            return Bedon::find($record->bedon)->name;
          })
          ->description(function (Victim $record){
            if (!$record->bedon) return null;
             $rec=Bedon::find($record->bedon);
             $data=$rec->who;
             if ($rec->tel) $data=$data.'/'.$rec->tel;
            return $data;
          }),
        TextColumn::make('mother')
          ->label('الأم')
          ->toggleable()
          ->state(function (Victim $record){
            if ($record->mother_id) return $record->sonOfMother->FullName;
            else return '-';
          })
          ->description(function (Victim $record){
            $data='';
            if ($record->mafkoden) $data=Mafkoden::find($record->mafkoden)->mother;
            if ($record->bedon) $data=$data.'/'.Bedon::find($record->bedon)->mother;
            return $data;
          }),
        TextColumn::make('User.name')
         ->toggleable(isToggledHiddenByDefault: true)
         ->label('By'),
        ImageColumn::make('image')
          ->toggleable()
          ->placeholder('الصورة')
          ->tooltip('اضغط للإدخال او التعديل')
          ->action(
            Action::make('Upload')
            ->form([
              Forms\Components\FileUpload::make('image')
                ->directory('form-attachments'),
            ])
            ->action(function (array $data,Victim $record,){
                $record->update(['image'=>$data['image'], ]);
            })
          )
          ->label('')
          ->circular(),
      ])

      ->filters([
        SelectFilter::make('aircraft')

          ->form([
               Select::make('family_id')
                 ->hiddenLabel()
                 ->prefix('العائلة')
                 ->relationship('Family','FamName')
                 ->afterStateUpdated(function ($state){
                   self::$family_id=$state;
                 })
                 ->searchable()
                 ->preload()
                 ->live()
                 ->columnSpan(2),
               Select::make('street_id')
                 ->hiddenLabel()
                 ->prefix('العنوان')
                 ->preload()
                 ->searchable()
                 ->columnSpan(2)
                 ->relationship('Street','StrName'),
            \Filament\Forms\Components\Actions::make([
               Forms\Components\Actions\Action::make('printFamily')
                 ->label('طباعة')
                 ->visible(function (Get $get){
                   return $get('family_id')!=null;
                 })
                 ->icon('heroicon-m-printer')
                 ->url(function (Get $get) {
                   return route('pdffamily', ['family_id' => $get('family_id')]);
                 } ),
              Forms\Components\Actions\Action::make('whoSer')
                ->label('بحث عن المبلغين')
                ->size(ActionSize::ExtraLarge)
                ->badge()
                ->icon('heroicon-s-magnifying-glass')
                ->color('success')
                ->modalContent(view('filament.user.pages.who-search-widget'))
                ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة')->icon('heroicon-s-arrow-uturn-left'))
                ->modalSubmitAction(false)



              ])->verticallyAlignCenter()->columnSpan(2),
          ])
          ->columns(6)
          ->columnSpanFull()
          ->query(function (Builder $query, array $data) {

            $family_id = (int) $data['family_id'];
            $street_id   = (int) $data['street_id'];


            if (!empty($family_id)) {
              $query->where('family_id',$family_id);
            }
            if (!empty($street_id)) {
              $query->where('street_id',$street_id);
            }
          }),


      ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
      ->filtersTriggerAction(
        fn (Tables\Actions\Action $action) => $action
          ->button()
          ->label('إضفط هنا لفتح واغلاق الفلترة'),
      )
      ->actions([
        Tables\Actions\EditAction::make()
          ->icon('heroicon-s-pencil')
          ->iconButton()
          ->color('blue'),
        Action::make('View Information')
          ->iconButton()
          ->modalHeading('')
          ->modalWidth(MaxWidth::FiveExtraLarge)
          ->icon('heroicon-s-eye')
          ->modalSubmitAction(false)
          ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة'))
          ->infolist([
            Section::make()
              ->schema([
                Section::make()
                  ->schema([
                    TextEntry::make('FullName')
                      ->color(function (Victim $record){
                        if ($record->male=='ذكر') return 'primary';  else return 'Fuchsia';})
                      ->columnSpanFull()
                      ->weight(FontWeight::ExtraBold)
                      ->size(TextEntry\TextEntrySize::Large)
                      ->label(''),
                    TextEntry::make('sonOfFather.FullName')
                      ->visible(function (Victim $record){
                        return $record->father_id;
                      })
                      ->color('info')
                      ->label('والده')
                      ->size(TextEntry\TextEntrySize::Large)

                      ->columnSpanFull(),
                    TextEntry::make('sonOfMother.FullName')
                      ->visible(function (Victim $record){
                        return $record->mother_id;
                      })
                      ->color('Fuchsia')
                      ->label('والدته')
                      ->size(TextEntry\TextEntrySize::Large)

                      ->columnSpanFull(),

                    TextEntry::make('husband.FullName')
                      ->visible(function (Victim $record){
                        return $record->wife_id;
                      })
                      ->color('Fuchsia')
                      ->label('زوجته')
                      ->size(TextEntry\TextEntrySize::Large)
                      ->separator(',')
                      ->columnSpanFull(),
                    TextEntry::make('husband2.FullName')
                      ->visible(function (Victim $record){
                        return $record->wife2_id;
                      })
                      ->color('Fuchsia')
                      ->label('زوجته الثانية')
                      ->size(TextEntry\TextEntrySize::Large)
                      ->columnSpanFull(),
                    TextEntry::make('wife.FullName')
                      ->visible(function (Victim $record){
                        return $record->husband_id;
                      })
                      ->label('زوجها')
                      ->badge()
                      ->separator(',')
                      ->columnSpanFull(),

                    TextEntry::make('father.Name1')
                      ->visible(function (Victim $record){
                        return $record->is_father;
                      })
                      ->label('أبناءه')
                      ->color(function( )  {
                        self::$ser++;

                        switch (self::$ser){
                          case 1: $c='success';break;
                          case 2: $c='info';break;
                          case 3: $c='yellow';break;
                          case 4: $c='rose';break;
                          case 5: $c='blue';break;
                          case 6: $c='Fuchsia';break;
                          default: $c='primary';break;
                        }
                        return $c;

                      })
                      ->badge()
                      ->separator(',')
                      ->columnSpanFull(),
                    TextEntry::make('mother.Name1')
                      ->visible(function (Victim $record){
                        return $record->is_mother;
                      })
                      ->label('أبناءها')
                      ->badge()
                      ->separator(',')
                      ->columnSpanFull(),

                    TextEntry::make('Family.FamName')
                      ->color('info')
                      ->label('العائلة'),
                    TextEntry::make('Family.Tribe.TriName')
                      ->color('info')
                      ->label('القبيلة'),
                    TextEntry::make('Street.StrName')
                      ->color('info')
                      ->label('العنوان'),
                    TextEntry::make('Street.Area.AreaName')
                      ->color('info')
                      ->label('المحلة'),

                    TextEntry::make('Qualification.name')
                      ->visible(function (Model $record){
                        return $record->qualification_id;
                      })
                      ->color('info')
                      ->label('المؤهل'),
                    TextEntry::make('Job.name')
                      ->visible(function (Model $record){
                        return $record->job_id;
                      })
                      ->color('info')
                      ->label('الوظيفة'),
                    TextEntry::make('VicTalent.Talent.name')
                      ->visible(function (Model $record){
                        return VicTalent::where('victim_id',$record->id)->exists() ;
                      })

                      ->color('info')
                      ->label('المواهب'),
                    TextEntry::make('notes')
                      ->label('')

                  ])
                  ->columns(2)
                  ->columnSpan(2),

                ImageEntry::make('image')
                  ->label('')

                    ->height(400)
                  ->square()
                  ->columnSpan(2)


              ])->columns(4)
          ])
          ->slideOver(),
        Action::make('RetTasreeh')
         ->label('ارجاع')
         ->requiresConfirmation()
          ->modalSubmitActionLabel('نعم')
          ->modalCancelActionLabel('لا')
          ->fillForm(fn (Victim $record): array => [
            'family_id' => $record->family_id,
            'id' => $record->id,
          ])
          ->form([
            Forms\Components\TextInput::make('family_id')
             ->label('كود العائلة')
             ->hidden()
             ->live()
             ,
            Forms\Components\TextInput::make('id')
              ->label('id')
              ->hidden()
              ->live()
            ,
            Forms\Components\Select::make('victim_id')
            ->label('فالمنظومة')
            ->searchable()
            ->autofocus()
            ->preload()
            ->required()
              ->options(fn (Forms\Get $get): Collection => Victim::query()
                ->where('family_id', $get('family_id'))
                ->where('id','!=',$get('id'))
                ->pluck('FullName', 'id'))
          ])
          ->visible(fn(Victim $record)=>$record->fromwho!='المنظومة')
          ->action(function (array $data,Victim $record): void {
           if ($record->fromwho=='بتصريح')
              {Tasreeh::find($record->tasreeh)->update(['victim_id'=>$data['victim_id']]);
               Victim::find($data['victim_id'])->update(['tasreeh'=>$record->tasreeh]);}
            if ($record->fromwho=='بدون')
            {Bedon::find($record->bedon)->update(['victim_id'=>$data['victim_id']]);
              Victim::find($data['victim_id'])->update(['bedon'=>$record->bedon]);}
            if ($record->fromwho=='مفقودين')
            {Mafkoden::find($record->mafkoden)->update(['victim_id'=>$data['victim_id']]);
              Victim::find($data['victim_id'])->update(['mafkoden'=>$record->mafkoden]);}


               $record->delete();
         })

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
          'view' => Pages\ViewVictim::route('/{record}'),
        ];
    }
}
