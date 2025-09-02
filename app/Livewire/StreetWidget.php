<?php

namespace  App\Livewire;

use App\Livewire\Traits\PublicTrait;
use App\Models\Area;
use App\Models\Family;
use App\Models\Road;
use App\Models\Street;
use App\Models\Victim;
use Filament\Actions\StaticAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class StreetWidget extends BaseWidget
{
    use PublicTrait;
  protected int | string | array $columnSpan=1;
  protected static ?int $sort=3;

  public $area_id=null;
  public $road_id=null;
  public $areaName=null;
  public $pre=null;
  public $onlyLibyan=false;

  #[On('take_road')]
  public function take_road($road_id,$areaName){
    $this->road_id=$road_id;
    $this->areaName=$areaName;
    $this->area_id=null;
    $this->pre=' الشوارع الفرعية لـ : '.$this->areaName;
  }
  #[On('take_area')]
  public function take_area($area_id,$areaName){
    $this->area_id=$area_id;
    $this->areaName=$areaName;
    $this->road_id=null;
      $this->pre=' الشوارع الفرعية بمحلة : '.$this->areaName;
  }
    #[On('take_libyan')]
    public function take_libyan($onlyLibyan){
        $this->onlyLibyan=$onlyLibyan;
    }

    public function table(Table $table): Table
    {
        return $table
          ->query(function (Street $tribe) {

            $tribe=Street::query()
              ->when($this->road_id,function ($q){
                $q->where('road_id',$this->road_id);
              })

              ->when($this->area_id,function ($q){
                $q->where('area_id',$this->area_id);
              })
                ->when(!$this->area_id && !$this->road_id,function ($q){
                    $q->where('id',null);
                })     ;
            return $tribe;
          }
          )
            ->queryStringIdentifier('street')
          ->heading(function () {return new HtmlString('<div class="text-primary-400 text-lg ">'.$this->pre.'</div>');} )
          ->defaultPaginationPageOption(16)
           ->paginationPageOptions([5,10,16,25,50,100])
            ->emptyStateHeading('قم بالاختيار من قائمة المحلات والشوارع الرئيسية')
            ->emptyStateIcon('heroicon-o-arrow-right')
            ->defaultSort('victim_count','desc')
          ->striped()

          ->columns([
            TextColumn::make('StrName')
              ->sortable()
              ->color('blue')
              ->searchable()
              ->label('العنوان'),
              TextColumn::make('victim_count')
                  ->color('warning')
                  ->sortable()
                  ->label('العدد')
                  ->counts('Victim'),

              Tables\Columns\ImageColumn::make('image')
                  ->label('')
                  ->action(
                      Tables\Actions\Action::make('show_images')
                          ->visible(function ($record){return $record->image !=null;})
                          ->label(' ')
                          ->modalSubmitAction(false)
                          ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة'))
                          ->infolist([
                              ImageEntry::make('image')
                                  ->label('')
                                  ->stacked()
                                  ->label('')
                                  ->height(500)
                          ])
                  )
                  ->limit(1)



          ])
            ->actions([
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->iconButton()
                    ->color('primary')
                    ->action(function (Street $record){

                        \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfStreets',
                            [
                                'victims' => Victim::where('street_id',$record->id)
                                    ->when($this->onlyLibyan,function ($q){
                                        $q->whereIn('family_id',Family::where('country_id',1)->pluck('id'));})
                                    ->orderBy('family_id','desc')->orderBy('masterKey')->get(),])
                            ->footerView('PDF.footer')
                            ->margins(20,10,20,10)
                            ->save(public_path().'/streets.pdf');

                        return Response::download(public_path().'/streets.pdf',
                            'filename.pdf', self::ret_spatie_header());

                    }),
            ])
            ;
    }
}
