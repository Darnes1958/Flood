<?php

namespace App\Filament\Widgets;

use App\Livewire\Traits\PublicTrait;
use App\Models\Victim;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\TextColumn;

class GuestsWidget extends BaseWidget
{
    use PublicTrait;
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort=11;
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Victim $tribe) {
                $tribe=Victim::where('guests',1);
                return $tribe;
            }
            )
            ->headerActions([
                Action::make('printnewGuests')
                    ->label('طباعة')
                    ->color('success')
                    ->icon('heroicon-m-printer')
                    ->action(function (){

                        \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfGuests',
                            ['victims' => Victim::where('guests',1)->orderBy('image2','desc')->get(),])
                            ->landscape()

                            ->save(public_path().'/Guests.pdf');

                        return Response::download(public_path().'/Guests.pdf',
                            'filename.pdf', self::ret_spatie_header());

                    }),
            ])
            ->queryStringIdentifier('guests')
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">ضيوف ('.Victim::where('guests',1)->count().')</div>'))
            ->defaultPaginationPageOption(5)
            ->defaultSort('street_id')
            ->striped()
            ->columns([
                TextColumn::make('FullName')
                    ->sortable()
                    ->color('blue')
                    ->searchable()
                    ->label('الاسم'),
                TextColumn::make('notes')
                    ->color('warning')
                    ->action(
                        Action::make('updatenote')
                         ->fillForm(fn (Victim $record): array => [
                                'notes'=>$record->notes,
                            ])
                         ->form([ Textarea::make('notes'), ])
                         ->action(function (array $data,Victim $record){
                            $record->update(['notes'=>$data['notes']]);
                        })
                    )
                    ->sortable()
                    ->label('البيان'),
                ImageColumn::make('image2')
                    ->height(160)
                    ->label('')
                    ->limit(1)
                    ->circular(),

            ]);
    }
}
