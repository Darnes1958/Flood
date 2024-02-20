<?php

namespace App\Filament\Pages;

use App\Models\Family;
use App\Models\Tribe;
use App\Models\Victim;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class RepTripFam extends Page implements HasTable
{
    use \Filament\Tables\Concerns\InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';



    protected static ?string $navigationLabel='قبائل وعائلات';
    protected ?string $heading="";



    protected static string $view = 'filament.pages.rep-trip-fam';

    public function table(Table $table):Table
    {
        return $table
            ->query(function (Family $family)  {
                $family=Family::where('id','!=',null);


                return  $family;
            })
            ->columns([
                TextColumn::make('Tribe.TriName')
                    ->sortable()
                    ->color('primary')
                    ->label('القبيلة'),
                TextColumn::make('FamName')
                    ->sortable()
                    ->color('blue')
                    ->label('العائلة'),

                TextColumn::make('victims_count')
                    ->counts('victims')

                    ->color('warning')
                    ->sortable()
                    ->label('العدد')
            ])

            ->defaultSort('victims_count','desc')
            ->striped();
    }
}
