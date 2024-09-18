<?php

namespace App\Livewire;

use App\Models\Talent;
use App\Models\VicTalent;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;

class TalentVictimWidget extends BaseWidget
{
    protected int | string | array $columnSpan=4;
    protected static ?string $heading='';
    public $talent_id = null;
    public $talentType=null ;
    public $title=null ;
    #[On('TakeTalentType')]
    public function TakeTalentType($talentType){
        $this->talentType=$talentType;
        $this->talent_id=null;
        $this->title=Talent::where('talentType',$talentType)->first()->talentType->name;
    }
    #[On('TakeTalentId')]
    public function TakeTalentId($talent_id){
        $this->talent_id=$talent_id;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (VicTalent $query) {
                $query=VicTalent::
                when($this->talent_id,function ($q){
                    $q->where('talent_id',$this->talent_id);
                })
                ->whereIn('talent_id',Talent::where('talentType',$this->talentType)->pluck('id'));
                return $query;
            })
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,20,50])
            ->emptyStateHeading('')
            ->columns([
                TextColumn::make('Victim.FullName')
                ->searchable()
                ->label(''),
                ImageColumn::make('Victim.image2')
                    ->label('')
                    ->circular()
                    ->limit(1)
                    ->height(80),
            ])
            ;

    }
}
