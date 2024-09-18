<?php

namespace App\Livewire;


use App\Models\Talent;
use App\Models\VicTalent;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class TalentTypeWidget extends BaseWidget
{
    protected int | string | array $columnSpan=2;
    protected static ?string $heading='';
    public function getTableRecordKey(Model $record): string
    {
        return Talent::where('talentType',$record->talentType->value)->first()->id;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Talent $q){
                $q=Talent::groupBy('talentType')->selectRaw('count(*) as count, talentType');
                return $q;
            }
            )
            ->defaultSort('talentType')
            ->paginated(false)
            ->columns([
                TextColumn::make('talentType')
                ->label('')
                    ->action(function (Talent $record){
                        $this->dispatch('TakeTalentType',talentType: $record->talentType->value);
                    })
                ->badge(),
                TextColumn::make('victims')
                    ->label('')
                    ->color('warning')
                    ->state(function (Talent $record){
                        $talents=Talent::where('talentType',$record->talentType->value)->distinct('id')->pluck('id');
                        return VicTalent::whereIn('talent_id',$talents)->count();
                    }),
                TextColumn::make('count')
                    ->label('')
                    ->formatStateUsing(fn (Talent $record): View => view(
                        'filament.user.pages.img-only',
                        ['record' => $record,'who'=>'talent'],
                    )),

            ]);
    }
}
