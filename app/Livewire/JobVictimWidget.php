<?php

namespace App\Livewire;

use App\Livewire\Traits\PublicTrait;
use App\Models\Job;



use App\Models\Victim;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\On;
use Spatie\LaravelPdf\Enums\Format;

class JobVictimWidget extends BaseWidget
{
    use PublicTrait;
    protected int | string | array $columnSpan=4;
    protected static ?string $heading='';
    public $job_id = null;
    public $jobType=null ;
    public $title=null ;
    #[On('TakeJobType')]
    public function TakeJobType($jobType){
        $this->jobType=$jobType;
        $this->job_id=null;
        $this->title=Job::where('jobType',$jobType)->first()->jobType->name;
    }
    #[On('TakeJobId')]
    public function TakeJobId($job_id){
        $this->job_id=$job_id;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Victim $query) {
                $query=Victim::
                when($this->job_id,function ($q){
                    $q->where('job_id',$this->job_id);
                })
                    ->whereIn('job_id',Job::where('jobType',$this->jobType)->pluck('id'));
                return $query;
            })
            ->headerActions([
                Action::make('printTalent')
                    ->label('طباعة')
                    ->color('success')
                    ->icon('heroicon-m-printer')
                    ->action(function (){



                            \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfVicJob',
                                ['victims' => Victim::
                                when($this->job_id,function ($q){
                                    $q->where('job_id',$this->job_id);
                                })->whereIn('job_id',job::where('jobType',$this->jobType)->pluck('id'))->get(),
                                    'job'=>job::find($this->job_id),

                                ])

                                ->format(Format::A5)
                                ->save(public_path().'/job.pdf');

                        return Response::download(public_path().'/job.pdf',
                            'filename.pdf', self::ret_spatie_header());

                    }),
            ])
            ->defaultPaginationPageOption(8)
            ->paginationPageOptions([5,8,20,50])
            ->emptyStateHeading('')
            ->columns([
                TextColumn::make('FullName')
                    ->searchable()
                    ->label(''),
                ImageColumn::make('image2')
                    ->label('')
                    ->circular()
                    ->limit(1)
                    ->height(80),
            ])
            ;

    }
}
