<?php

namespace App\Livewire;

use App\Models\Job;

use App\Models\Victim;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class JobTypeWidget extends BaseWidget
{
    protected int | string | array $columnSpan=2;
    protected static ?string $heading='';
    public function getTableRecordKey(Model $record): string
    {
        return Job::where('jobType',$record->jobType->value)->first()->id;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Job $q){
                $q=Job::groupBy('jobType')->selectRaw('count(*) as count, jobType');
                return $q;
            }
            )
            ->defaultSort('jobType')
            ->paginationPageOptions([5,10,20,50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('jobType')
                    ->label('')
                    ->action(function (Job $record){
                        $this->dispatch('TakeJobType',jobType: $record->jobType->value);
                    })
                    ->badge(),
                TextColumn::make('victims')
                    ->label('')
                    ->color('warning')
                    ->state(function (Job $record){
                        $jobs=Job::where('jobType',$record->jobType->value)->distinct('id')->pluck('id');
                        return Victim::whereIn('job_id',$jobs)->count();
                    }),
                TextColumn::make('count')
                    ->label('')
                    ->formatStateUsing(fn (Job $record): View => view(
                        'filament.user.pages.img-only',
                        ['record' => $record,'who'=>'job'],
                    )),

            ]);
    }
}
