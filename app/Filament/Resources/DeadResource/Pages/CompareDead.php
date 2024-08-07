<?php

namespace App\Filament\Resources\DeadResource\Pages;

use App\Filament\Resources\DeadResource;
use App\Livewire\BedonWidget;
use App\Livewire\DeadWidget;
use App\Livewire\VictimWidget;
use App\Livewire\WhoWidget;
use App\Models\Family;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Resources\Pages\Page;

class CompareDead extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = DeadResource::class;

    protected static string $view = 'filament.resources.dead-resource.pages.compare-dead';
    protected ?string $heading="مقارنة بدون تصريح";

    public $family_id;
    public $with_victim=false;
    public $show_description=false;
    public $show_other=true;
    public $forign_only=false;

    public $familyData;

    public function mount(): void
    {
        $this->familyForm->fill(['show_other'=>true,'forign_only'=>false]);
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
                        ->optionsLimit(500)
                        ->options(function (Get $get) {
                            return Family::has('dead')
                                ->when($get('forign_only'),function ($q){
                                    $q->where('nation','!=','ليبيا');
                                })
                                ->pluck('FamName','id');
                        })
                        ->preload()
                        ->live()
                        ->searchable()
                        ->columnSpan(4)
                        ->afterStateUpdated(function ($state){
                            $this->family_id=$state;
                            $this->dispatch('updatefamily', family_id: $this->family_id,with_victim : $this->with_victim,
                                show_description: $this->show_description,who: 'dead',show_other: $this->show_other );
                        }),
                    Checkbox::make('with_victim')
                        ->label('إظهار المتطابق')
                        ->reactive()
                        ->afterStateUpdated(function ($state){
                            $this->with_victim=$state;
                            $this->dispatch('updatefamily', family_id: $this->family_id,with_victim : $this->with_victim,
                                show_description: $this->show_description,who: 'dead',show_other: $this->show_other);
                        }),
                    Checkbox::make('show_description')
                        ->label('إظهار التفاصيل')
                        ->reactive()
                        ->afterStateUpdated(function ($state){
                            $this->show_description=$state;
                            $this->dispatch('updatefamily', family_id: $this->family_id,with_victim : $this->with_victim,
                                show_description: $this->show_description,who: 'dead',show_other: $this->show_other);
                        }),
                    Checkbox::make('show_other')
                        ->label('إظهار بتصريح ومفقودين')
                        ->reactive()
                        ->afterStateUpdated(function ($state){
                            $this->show_other=$state;
                            $this->dispatch('updatefamily', family_id: $this->family_id,with_victim : $this->with_victim,
                                show_description: $this->show_description,who: 'dead',show_other: $this->show_other);
                        })->columnSpan(2),
                    Checkbox::make('forign_only')
                        ->label('اجانب فقط')
                        ->reactive()
                        ->afterStateUpdated(function ($state){
                            $this->forign_only=$state;
                        })


                ])->columns(9)
        ];
    }

    public static function getWidgets(): array
    {
        return [
            DeadWidget::class,
            VictimWidget::class,
            WhoWidget::class,
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            DeadWidget::make([
                'family_id'=>$this->family_id,'without'=>$this->with_victim,
            ]),
            VictimWidget::make([
                'family_id'=>$this->family_id,'without'=>$this->with_victim,'who'=>'dead','show_other'=> $this->show_other,
            ]),
            WhoWidget::make(),
        ];
    }
}
