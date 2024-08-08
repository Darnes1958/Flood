<?php

namespace App\Filament\Resources\DeadResource\Pages;

use App\Filament\Resources\DeadResource;
use App\Models\Bedon;
use App\Models\Dead;
use App\Models\Family;
use App\Models\Victim;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ModifyDead extends Page implements HasTable,HasForms
{
    use InteractsWithTable, InteractsWithForms;
    protected static string $resource = DeadResource::class;

    protected static string $view = 'filament.resources.dead-resource.pages.modify-dead';
    protected ?string $heading="";

  public $family_id;
  public $newFamily_id;
  public $familyData;
  public $families;

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
                    ->options(Family::
                              whereIn('id',Dead::where('ok',0)->select('family_id'))
                              ->pluck('FamName','id'))
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
        ->query(function (Dead $mafkoden) {
            $mafkoden = Dead::where('family_id',$this->family_id)->where('ok',0)
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
              ->action(
                Action::make('updateFamily')
                  ->form([
                    Select::make('family_id')
                      ->options(Family::all()->pluck('FamName','id'))
                      ->label('العائلة')
                      ->searchable()
                      ->preload()
                      ->live()
                  ])
                  ->fillForm(fn (Dead $record): array => [
                    'family_id' => $record->family_id,
                  ])
                  ->modalCancelActionLabel('عودة')
                  ->modalSubmitActionLabel('تحزين')
                  ->modalHeading('تعديل العائلة')
                  ->action(function (array $data,Dead $record,){
                    $record->update(['family_id'=>$data['family_id']]);
                  })
              )
                ->toggleable()
                ->label('العائلة'),
          TextColumn::make('who')
            ->label('المبلغ')
            ->searchable(),
          TextColumn::make('mother')
            ->label('الام')
            ->searchable(),

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
