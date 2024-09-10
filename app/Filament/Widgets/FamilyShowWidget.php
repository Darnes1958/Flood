<?php

namespace App\Filament\Widgets;

use App\Models\BigFamily;
use App\Models\Familyshow;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class FamilyShowWidget extends BaseWidget
{
    protected int | string | array $columnSpan=1;
    protected static ?int $sort=2;

    public $big_family_id=null;

    #[On('take_big_family')]
    public function take_big_family($big_family_id){
        $this->big_family_id=$big_family_id;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Familyshow $tribe) {
                $tribe=Familyshow::query()
                    ->where('nation','ليبيا')
                    ->when($this->big_family_id,function ($q){
                        $q->where('bigfamily_id',$this->big_family_id);
                    });

                return $tribe;
            }
            )
            ->queryStringIdentifier('familieshows')
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب العائلات </div>'))
            ->defaultPaginationPageOption(5)
            ->defaultSort('victim_count','desc')
            ->striped()
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->action(function (Familyshow $record){
                        $this->dispatch('take_familyshow_id',familyshow_id: $record->id);
                    })
                    ->color('blue')
                    ->searchable()
                    ->label('العائلة'),
                TextColumn::make('victim_count')
                    ->color('warning')
                    ->sortable()
                    ->label('العدد')
                    ->counts('Victim'),
            ]);
    }
}
