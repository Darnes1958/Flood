<?php

namespace  App\Livewire;


use App\Livewire\Traits\PublicTrait;
use App\Models\Road;

use App\Models\Street;
use App\Models\Victim;
use Filament\Actions\StaticAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;


class Roadwidget extends BaseWidget
{
    use PublicTrait;
  protected int | string | array $columnSpan = 1;
    protected static ?int $sort=2;


    public function table(Table $table): Table
    {
        return $table
            ->query(function (Road $tribe) {
                $tribe=Road::query()
                  ;
                return $tribe;
            }
            )
            ->queryStringIdentifier('roads')
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب الشوارع الرئيسية</div>'))
            ->defaultPaginationPageOption(6)
            ->paginationPageOptions([6,10,16,25,50,100])


            ->defaultSort('victim_count','desc')
            ->striped()
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                  ->action(function (Road $record){
                    $this->dispatch('take_road',road_id: $record->id,areaName: $record->name);
                  })
                    ->color('blue')
                    ->searchable()
                    ->label('الشارع'),
                TextColumn::make('victim_count')
                    ->color('warning')
                    ->sortable()
                    ->label('العدد')
                    ->counts('Victim'),
                ImageColumn::make('image')
                    ->label('')
                    ->action(
                        Action::make('show_images')
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
                    ->action(function (Road $record){

                        \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfStreets',
                            [
                                'victims' => Victim::whereIn('street_id',Street::where('road_id',$record->id)->pluck('id'))
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
