<?php

namespace App\Livewire;

use App\Livewire\Traits\PublicTrait;
use App\Models\Talent;
use App\Models\VicTalent;
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

class TalentVictimWidget extends BaseWidget
{
    use PublicTrait;
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
            ->headerActions([
                Action::make('printTalent')
                    ->label('طباعة')
                    ->color('success')
                    ->icon('heroicon-m-printer')
                    ->action(function (){

                        if ($this->talentType==6)
                            \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfVicTalents6',
                                ['victims' => VicTalent::
                               whereIn('talent_id',Talent::where('talentType',$this->talentType)->pluck('id'))->get(),
                                    'talent'=>Talent::where('talentType',$this->talentType)->first()])

                                ->format(Format::A5)
                                ->save(public_path().'/Talent.pdf');

                         else

                        \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfVicTalents',
                            ['victims' => VicTalent::
                            when($this->talent_id,function ($q){
                                $q->where('talent_id',$this->talent_id);
                            })->whereIn('talent_id',Talent::where('talentType',$this->talentType)->pluck('id'))->get(),
                             'talent'=>Talent::find($this->talent_id),

                                ])

                            ->format(Format::A5)
                            ->save(public_path().'/Talent.pdf');

                        return Response::download(public_path().'/Talent.pdf',
                            'filename.pdf', self::ret_spatie_header());

                    }),
            ])
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
