<?php

namespace App\Filament\Resources;

use App\Enums\Marry;
use App\Filament\Resources\HadamResource\Pages;
use App\Filament\Resources\HadamResource\RelationManagers;
use App\Models\Hadam;
use App\Models\Map;
use Filament\Actions\StaticAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class HadamResource extends Resource
{
    protected static ?string $model = Hadam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel='محاضر الهدم';
    protected static ?string $pluralLabel='محاضر الهدم';

    public static function getNavigationBadge(): ?string
    {

            return static::getModel()::count();

    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              TextInput::make('no')
               ->label('الرقم'),
              DatePicker::make('date')
                ->label('التاريخ'),
              Select::make('wakeel_id')
                ->relationship('Wakeel','name')
                ->searchable()
                ->preload()
                ->createOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('وكيل النيابة')
                    ->maxLength(255),
                ])
                ->editOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('وكيل النيابة')
                    ->maxLength(255),
                ])
                ->label('وكيل النيابة'),
              TextInput::make('name')
                ->label('الاسم'),
              TextInput::make('mother')
                ->label('اسم الام'),
              DatePicker::make('birth')
                ->label('تاريخ الميلاد'),
              TextInput::make('nat_id')
                ->label('الرقم الوطني'),
              Select::make('h_area_id')
                ->relationship('H_area','name')
                ->createOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('محل الاقامة')
                    ->maxLength(255),
                ])
                ->editOptionForm([
                  TextInput::make('name')
                    ->required()
                    ->label('محل الاقامة')
                    ->maxLength(255),
                ])
                ->label('محل الاقامة'),
              Select::make('marry')
                ->options(Marry::class)
                ->label('الحالة الاجتماعية'),
              TextInput::make('damages')
                ->label('الاضرار'),
              TextInput::make('place')
                ->label('مكان العقار'),
              Select::make('place_type_id')
                ->relationship('Place_type','name')
                ->label('نوع العفار'),
              TextInput::make('mostand')
                ->label('مستند الملكية'),
              TextInput::make('anyother')
                ->label('ملحقات العقار'),
              TextInput::make('notes')
                ->label('ملاحظات'),
              TextInput::make('location')
                ->label('موقع قوقل'),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
          ->toggleColumnsTriggerAction(
            fn (Action $action) => $action
              ->button()
              ->label('إخفاء وإظهار الا‘عمدة'),
          )
            ->columns([
              TextColumn::make('no')
                ->label('الرقم'),
              TextColumn::make('date')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('التاريخ'),
              TextColumn::make('Wakeel.name')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('وكيل النيابة'),
              TextColumn::make('name')
                ->description(function (Hadam $record) {
                  if ($record->mother) return $record->mother;
                })
                ->searchable()
                ->sortable()
                ->label('الاسم'),
              TextColumn::make('birth')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('تاريخ الميلاد'),
              TextColumn::make('nat_id')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('الرقم الوطني'),
              TextColumn::make('H_area.name')
                ->toggleable()
                ->description(function (Hadam $record) {
                  if ($record->place) return $record->place;
                })
                ->label('محل الاقامة'),
              TextColumn::make('marry')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('الحالة'),
              TextColumn::make('damages')
                ->words(10)
                ->toggleable()
                ->label('الاضرار'),
              TextColumn::make('Place_Type.name')
                ->toggleable()
                ->description(function (Hadam $record) {
                  if ($record->anyother) return $record->mostand.' / '.$record->anyother;
                  else return $record->mostand;
                })
                  ->words(6)
                ->label('نوع العقار'),

              TextColumn::make('notes')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('ملاحظات'),
              TextColumn::make('location')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('موقع قوقل'),

            ])
            ->filtersTriggerAction(
              fn (Action $action) => $action
                ->button()
                ->label('إضغط هنا للفلترة'),
            )
            ->filters([
                SelectFilter::make('H_area')
                    ->label('محل الاقامة')
                    ->searchable()
                    ->preload()
                    ->relationship('H_area','name'),
                SelectFilter::make('place_type')
                    ->label('نوع العقار')
                    ->searchable()
                    ->preload()
                    ->relationship('Place_type','name'),

                SelectFilter::make('Wakeel')
                    ->label('وكيل النيابة')
                    ->searchable()
                    ->preload()
                    ->relationship('Wakeel','name'),




            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make()
                 ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
                Tables\Actions\Action::make('View Information')
                    ->iconButton()
                    ->modalHeading('')
                    ->modalWidth(MaxWidth::FiveExtraLarge)
                    ->icon('heroicon-o-eye')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة'))
                    ->infolist([
                          Section::make('personal infomaation')
                              ->heading('')
                              ->schema([
                                  Section::make('personal')
                                      ->heading(new HtmlString('<div class="text-primary-400 text-md">بيانات شخصية</div>'))
                                      ->schema([
                                          TextEntry::make('name')
                                              ->color('danger')
                                              ->weight(FontWeight::Bold)
                                              ->size(TextEntry\TextEntrySize::Large)
                                              ->columnSpan(2)
                                              ->label('الاسم'),
                                          TextEntry::make('mother')
                                              ->columnSpan(2)
                                              ->color('info')
                                              ->label('الام'),
                                          TextEntry::make('nat_id')
                                              ->color('info')
                                              ->label('الرقم الوطني'),
                                          TextEntry::make('birth')
                                              ->color('info')
                                              ->label('تاريخ الميلاد'),
                                          TextEntry::make('marry')
                                              ->color('info')
                                              ->label('الحالة الاجتاعية'),
                                      ])->columns(7),
                                  TextEntry::make('wakeel')
                                      ->state(function (Hadam $record) {
                                          return new HtmlString(
                                              '<div class="flex">
                                     <div class="text-white text-md font-bold">&nbsp;&nbsp;وكيل النيابة &nbsp;&nbsp;</div>
                                     <div class=" text-md font-bold">'.$record->Wakeel->name.'</div>
                                   </div> '
                                          );
                                      })
                                      ->color('info')
                                      ->hiddenLabel()
                                      ->columnSpanFull(),
                                  Section::make('place')
                                      ->heading(new HtmlString('<div class="text-primary-400 text-md">بيانات العقار</div>'))
                                      ->schema([
                                          TextEntry::make('Place_Type.name')
                                              ->color('info')
                                              ->label('نوع العقار'),
                                          TextEntry::make('place')
                                              ->color('info')
                                              ->label('مكان العقار'),
                                          TextEntry::make('mostand')
                                              ->color('info')
                                              ->label('المستند'),
                                          TextEntry::make('anyother')
                                              ->hidden(function (Hadam $record){return $record->anyother==null;})
                                              ->color('info')
                                              ->label('ملحقات العقار'),
                                      ])->columns(4),


                                  TextEntry::make('damages')
                                      ->state(function (Hadam $record) {
                                          return new HtmlString(
                                              '<div class="flex">
                                     <div class="text-primary-400 text-md font-bold">الأضرار &nbsp;&nbsp;</div>
                                     <div class=" text-md font-bold">'.$record->damages.'</div>
                                   </div> '
                                          );
                                      })
                                      ->color('danger')
                                      ->hiddenLabel()
                                      ->columnSpanFull(),


                                  TextEntry::make('notes')
                                      ->hiddenLabel()
                                      ->columnSpanFull()
                                      ->state(function (Hadam $record) {
                                          return new HtmlString(
                                              '<div class="flex">
                                     <div class="text-primary-400 text-md font-bold">ملاحظات &nbsp;&nbsp;</div>
                                     <div class="text-info-400 text-md font-bold">'.$record->notes.'</div>
                                   </div> '
                                          );
                                      })
                                      ->color('danger')
                                      ->hidden(function (Hadam $record){return $record->notes==null;}),

                              ])
                              ->columnSpanFull()
                        ])
                    ->slideOver(),
                Action::make('عرض')
                    ->hidden(function (Hadam $record) {return $record->location===null; })
                    ->modalHeading(false)
                    ->action(fn (Hadam $record) => $record->id())
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة'))
                    ->modalContent(fn (Hadam $record): View => view(
                        'filament.pages.views.view-map',
                        ['address' => $record->location,],
                    ))
                    ->icon('heroicon-o-map-pin')
                    ->iconButton(),
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
            'index' => Pages\ListHadams::route('/'),
            'create' => Pages\CreateHadam::route('/create'),
            'edit' => Pages\EditHadam::route('/{record}/edit'),
            //'view' => Pages\ViewHadam::route('/{record}'),

        ];
    }
}
