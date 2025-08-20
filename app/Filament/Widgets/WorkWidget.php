<?php

namespace App\Filament\Widgets;

use App\Livewire\Traits\PublicTrait;
use App\Models\Victim;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;

class WorkWidget extends BaseWidget
{
    use PublicTrait;
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort=10;


    public function table(Table $table): Table
    {
        return $table
            ->query(function (Victim $tribe) {
                $tribe=Victim::where('inWork',1);
                return $tribe;
            }
            )
            ->headerActions([
                Action::make('printnewGuests')
                    ->label('طباعة')
                    ->color('success')
                    ->icon('heroicon-m-printer')
                    ->action(function (){

                        \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfWork',
                            ['victims' => Victim::where('inWork',1)->get(),])
                            ->landscape()

                            ->save(public_path().'/work.pdf');

                        return Response::download(public_path().'/work.pdf',
                            'filename.pdf', self::ret_spatie_header());

                    }),
            ])
            ->queryStringIdentifier('inWork')
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">في العمل ('.Victim::where('inWork',1)->count().')</div>'))
            ->defaultPaginationPageOption(5)

            ->defaultSort('street_id')
            ->striped()
            ->columns([
                TextColumn::make('FullName')
                    ->sortable()
                    ->color('blue')
                    ->searchable()
                    ->label('الاسم')
                    ->formatStateUsing(fn (Victim $record): View => view(
                        'filament.user.pages.data-with-images',
                        ['record' => $record],
                    )),
                TextColumn::make('Street.StrName')
                    ->color('warning')
                    ->sortable()
                    ->label('العنوان'),
                ImageColumn::make('image2')
                    ->height(160)
                    ->label('')
                    ->limit(1)
                    ->circular(),
            ]);
    }
}
