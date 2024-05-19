<?php

namespace App\Filament\Resources\TasreehResource\Pages;

use App\Filament\Resources\TasreehResource;

use App\Models\Family;
use App\Models\Tasreeh;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ModifyTasreeh extends Page implements HasTable,HasForms
{
    use InteractsWithTable,InteractsWithForms;
    protected static string $resource = TasreehResource::class;

    protected static string $view = 'filament.resources.tasreeh-resource.pages.modify-tasreeh';
    protected ?string $heading="";

    public $family_id;
    public $newFamily_id;
    public $familyData;

    public function mount(): void
    {
        $this->familyForm->fill([]);
    }

    protected function getForms(): array
    {
        return array_merge(parent::getForms(), [
            "familyForm" => $this->makeForm()
                ->model(Family::class)
                ->schema($this->getFamilyFormSchema())
                ->statePath('familyData'),

        ]);
    }

    protected function getFamilyFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    Select::make('family_id')
                        ->hiddenLabel()
                        ->prefix('العائلة')
                        ->options(Family::all()->pluck('FamName','id'))
                        ->preload()
                        ->live()
                        ->searchable()
                        ->columnSpan(2)
                        ->afterStateUpdated(function ($state){
                            $this->family_id=$state;

                        }),

                    Select::make('newFamily_id')
                        ->hiddenLabel()
                        ->prefix('العائلة الجديدة')
                        ->prefixIcon('heroicon-m-pencil')
                        ->prefixIconColor('info')

                        ->options(Family::all()->pluck('FamName','id'))
                        ->preload()
                        ->live()
                        ->searchable()
                        ->columnSpan(2)
                        ->afterStateUpdated(function ($state){
                            $this->newFamily_id=$state;
                        }),
                ])->columns(6)
        ];
    }
    public function table(Table $table):Table
    {
        return $table
            ->query(function (Tasreeh $mafkoden) {
                $mafkoden = Tasreeh::where('family_id',$this->family_id)
                ;
                return $mafkoden;
            })
            ->striped()
            ->columns([
                TextColumn::make('ser')
                    ->rowIndex()
                    ->label('ت'),
                TextColumn::make('id')
                    ->label('الرقم الألي')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('الاسم بالكامل')
                    ->searchable(),
                TextColumn::make('Family.FamName')
                    ->sortable()
                    ->toggleable()
                    ->label('العائلة'),
                TextColumn::make('sex')
                    ->state(function (Tasreeh $record): string {
                        if ($record->sex==1) return 'ذكر';
                        if ($record->sex==2) return 'أنثي';
                    })
                    ->action(function (Tasreeh $record): void {
                        if ($record->sex==1) $newMale=2; else $newMale=1;
                        $record->update(['sex'=>$newMale]);
                    })
                    ->label('الجنس')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ذكر' => 'success',
                        'أنثي' => 'Fuchsia',
                    }),

            ])
            ->bulkActions([


                BulkAction::make('editFamily')
                    ->deselectRecordsAfterCompletion()
                    ->label('تعديل العائلة')
                    ->hidden(!$this->newFamily_id)

                    ->action(fn (Collection $records) => $records->each->update([
                        'family_id'=>$this->newFamily_id
                    ])),


            ])
            ;
    }
}
