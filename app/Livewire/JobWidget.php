<?php

namespace App\Livewire;

use App\Models\Job;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class JobWidget extends BaseWidget
{
    protected int | string | array $columnSpan=2;
    protected static ?string $heading='';
    public $jobType=null ;
    public $title=null ;
    #[On('TakeJobType')]
    public function TakeJobType($jobType){

        $this->jobType=$jobType;
        $this->title=Job::where('jobType',$jobType)->first()->jobType->name;
    }
    public function table(Table $table): Table
    {
        return $table
            ->heading(function () {return new HtmlString('<div class="text-white text-lg ">'.$this->title.'</div>');} )
            ->emptyStateHeading('انقر لعرض التفاصيل')
            ->emptyStateIcon('heroicon-o-arrow-long-right')
            ->query(function (Job $query) {
                $query= Job::query()
                    ->where('jobType',$this->jobType)
                    ->whereHas('Victim');
                return $query;
            }

            )
            ->defaultSort('victim_count','desc')
            ->paginationPageOptions([5,8,15,20,50])
            ->defaultPaginationPageOption(8)
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->action(function (Model $record) {
                        $this->dispatch('TakeJobId',job_id: $record->id);
                    })
                    ->label(''),
                Tables\Columns\ImageColumn::make('image')
                 ->label(''),
                Tables\Columns\TextColumn::make('victim_count')
                    ->label('')
                    ->counts('Victim')
            ]);
    }
}
