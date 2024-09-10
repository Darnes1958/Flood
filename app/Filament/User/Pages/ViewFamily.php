<?php

namespace App\Filament\User\Pages;

use App\Models\Balag;
use App\Models\Bedon;
use App\Models\Dead;
use App\Models\Family;
use App\Models\Familyshow;
use App\Models\Mafkoden;
use App\Models\Street;
use App\Models\VicTalent;
use App\Models\Victim;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ViewFamily extends Page implements HasTable,HasForms
{
    use InteractsWithTable,InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.view-family';

    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->is_admin;
    }
    public $family_id=null;
    public $familyshow_id;

    public $street_id=null;
    public $show='all';
    public $mother;
    public $count;
    public $notes=true;
    public $hasNotes=false;
    static $ser=0;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([

                        Select::make('familyshow_id')
                            ->hiddenLabel()
                            ->prefix('العائلة')
                            ->options(function () {
                                return Familyshow::query()->orderBy('name')->pluck('name', 'id');
                            })
                            ->preload()
                            ->live()
                            ->searchable()
                            ->columnSpan(2)
                            ->afterStateUpdated(function ($state){
                                $this->familyshow_id=$state;
                                $this->family_id=null;
                                $this->mother=Victim::where('familyshow_id',$state)->where('is_mother',1)->pluck('id')->all();
                            }),
                        Select::make('family_id')
                            ->hiddenLabel()
                            ->prefix('اللقب')
                            ->hidden(function (){
                                return $this->familyshow_id && Family::where('familyshow_id',$this->familyshow_id)->count()<=1;
                            })
                            ->options(function () {
                                if ($this->familyshow_id )
                                    return Family::query()->whereIn('familyshow_id',Familyshow::where('id',$this->familyshow_id)->pluck('id'))->pluck('FamName', 'id');
                                return Family::query()->pluck('FamName', 'id');
                            })
                            ->preload()
                            ->live()
                            ->searchable()
                            ->columnSpan(2)
                            ->afterStateUpdated(function ($state){
                                $this->family_id=$state;
                                $this->mother=Victim::where('family_id',$state)->where('is_mother',1)->pluck('id')->all();
                            }),

                        Select::make('street_id')
                            ->hiddenLabel()
                            ->prefix('العنوان')
                            ->options(Street::all()->pluck('StrName','id'))
                            ->preload()
                            ->live()
                            ->searchable()
                            ->columnSpan(2)
                            ->afterStateUpdated(function ($state){
                                $this->street_id=$state;
                            }),


                        Radio::make('show')
                            ->inline()
                            ->hiddenLabel()
                            ->inlineLabel(false)
                            ->reactive()
                            ->live()
                            ->columnSpan(3)
                            ->default('all')
                            ->afterStateUpdated(function ($state){
                                $this->show=$state;
                            })
                            ->options([
                                'all'=>'الكل',
                                'parent'=> 'أباء وأمهات',
                                'single'=>'افراد',
                                'father_only'=>'أباء',
                                'mother_only'=>'أمهات',
                            ]),



                        \Filament\Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('printBigFamily')
                                ->label('طباعة العائلة')
                                ->visible(function (Get $get){
                                    return $get('familyshow_id')!=null;
                                })
                                ->color('success')
                                ->icon('heroicon-m-printer')
                                ->url(function (Get $get) {
                                    return route('pdffamilyshow',
                                        ['familyshow_id' => $get('familyshow_id'),]);
                                } ),
                            \Filament\Forms\Components\Actions\Action::make('printFamily')
                                ->label('طباعة اللقب')
                                ->visible(function (Get $get){
                                    return $get('family_id')!=null;
                                })
                                ->icon('heroicon-m-printer')
                                ->url(function (Get $get) {
                                    return route('pdffamily',
                                        ['family_id' => $get('family_id'),
                                            'bait_id' => 0,]);
                                } ),

                        ])->columnSpan(2),

                    ])
                    ->columns(8),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->query(function (){
                return
                    Victim::query()->where('familyshow_id',$this->familyshow_id)  ;
            })
            ->columns([
                    ImageColumn::make('image2')
                        ->height(160)
                        ->limit(1)
                        ->circular(),
                Stack::make([
                    Panel::make([
                        TextColumn::make('FullName')
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->sortable(),
                        TextColumn::make('Name2')
                            ->formatStateUsing(fn (Victim $record): HtmlString =>
                            new HtmlString('<span >'.$record->year.'&nbsp;&nbsp;&nbsp; - '.$record->Street->StrName.'</span>')),
                        TextColumn::make('Name1')
                            ->formatStateUsing(fn (Victim $record): View => view(
                                'filament.user.pages.view-marry',
                                ['record' => $record],
                            )),

                        TextColumn::make('is_mother')
                            ->formatStateUsing(fn (Victim $record): View => view(
                                'filament.user.pages.view-sons',
                                ['record' => $record],
                            )),

                    ]),




                ]),

            ])
        ->contentGrid([
        'md' => 2,
        'xl' => 5,
    ]);
    }
}
