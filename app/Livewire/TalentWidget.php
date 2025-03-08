<?php

namespace App\Livewire;

use App\Enums\talentType;
use App\Models\Talent;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class TalentWidget extends BaseWidget
{
    protected int | string | array $columnSpan=2;
    protected static ?string $heading='';
    public $talentType=null ;
    public $title=null ;
    #[On('TakeTalentType')]
    public function TakeTalentType($talentType){

      $this->talentType=$talentType;
        $this->title=Talent::where('talentType',$talentType)->first()->talentType->name;
    }
    public function table(Table $table): Table
    {
        return $table
            ->heading(function () {return new HtmlString('<div class="text-white text-lg ">'.$this->title.'</div>');} )
            ->emptyStateHeading('انقر لعرض التفاصيل')
            ->emptyStateIcon('heroicon-o-arrow-long-right')
            ->query(function (Talent $query) {
                $query= Talent::query()
                    ->where('talentType',$this->talentType)
                    ->whereHas('Victalent');
                return $query;
            }

            )
            ->defaultSort('victalent_count','desc')
            ->paginated(false)
            ->columns([

                Tables\Columns\TextColumn::make('name')
                ->color(function (Model $record){
                    if ($record->talentType->name=='دارنس') return 'primary';
                    if ($record->talentType->name=='الافريقي') return 'success';
                    if ($record->talentType->name=='الهلال_الاحمر') return 'danger';
                    if ($record->talentType->name=='الكشافة') return 'Fuchsia';
                    if ($record->talentType->name=='مواهب') return 'blue';
                })
                ->action(function (Model $record) {
                    $this->dispatch('TakeTalentId',talent_id: $record->id);
                })
                ->label(''),
                Tables\Columns\ImageColumn::make('image')
                 ->label(''),
                Tables\Columns\TextColumn::make('victalent_count')
                ->label('')
                ->counts('Victalent')
            ]);
    }
}
