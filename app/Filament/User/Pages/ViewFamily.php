<?php

namespace App\Filament\User\Pages;

use App\Livewire\Traits\PublicTrait;
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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;

class ViewFamily extends Page implements HasTable,HasForms
{
    use InteractsWithTable,InteractsWithForms;
    use PublicTrait;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.view-family';

    protected static ?string $navigationLabel='عرض للضحايا بالصور';
    protected ?string $heading='';
    protected static ?int $navigationSort=2;
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
                                ->visible(false)
                                ->color('success')
                                ->icon('heroicon-m-printer')
                                ->url(function (Get $get) {
                                    return route('pdffamilyshow',
                                        ['familyshow_id' => $get('familyshow_id'),]);
                                } ),
                            \Filament\Forms\Components\Actions\Action::make('printBigFamilyNew')
                                ->label('طباعة العائلة ')
                                ->visible(function (Get $get){
                                    return $get('familyshow_id')!=null;
                                })
                                ->color('success')
                                ->icon('heroicon-m-printer')
                                ->action(function (Get $get){

                                    \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfAllVictims_5',
                                        [
                                            'familyshow_id' => $this->familyshow_id,])
                                        ->footerView('PDF.footer')

                                        ->save(public_path().'/bigFamily.pdf');

                                    return Response::download(public_path().'/bigFamily.pdf',
                                        'filename.pdf', self::ret_spatie_header());

                                })
                            ,

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

                            \Filament\Forms\Components\Actions\Action::make('printForeignWives')
                                ->label('طباعة الزوجات الأجنبيات ')

                                ->color('success')
                                ->icon('heroicon-m-printer')
                                ->action(function (){

                                    \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfForeignWives',
                                        [
                                            'victims' => Victim::whereIn('family_id',[10456,10457,303,306,10384,10404])
                                                ->orderBy('family_id','desc')->get(),])
                                        ->footerView('PDF.footer')
                                        ->margins(20,10,20,10)
                                        ->save(public_path().'/bigFamily.pdf');

                                    return Response::download(public_path().'/bigFamily.pdf',
                                        'filename.pdf', self::ret_spatie_header());

                                })
                            ,

                        ])->columnSpan(3),

                    ])
                    ->columns(8),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->query(function (){
                return
                    Victim::query()
                        ->when($this->familyshow_id ,function($q){
                            $q->where('familyshow_id',$this->familyshow_id);
                        })
                        ->when($this->family_id ,function($q){
                            $q->where('family_id',$this->family_id);
                        })
                        ->when($this->street_id,function($q){
                            $q->where('street_id',$this->street_id);
                        })
                        ->when($this->show=='parent',function ($q){
                            $q->where(function ($q){
                                $q->where('is_father',1)
                                    ->orwhere('is_mother',1);
                            });
                        })
                        ->when($this->show=='father_only',function ($q){
                            $q->where('is_father',1);
                        })
                        ->when($this->show=='mother_only',function ($q){
                            $q->where('is_mother',1);
                        })
                        ->when($this->show=='single',function ($q){
                            $q->where(function ($q){
                                $q->where('is_father',null)->orwhere('is_father',0);
                            })
                                ->where(function ($q){
                                    $q->where('is_mother',null)->orwhere('is_mother',0);
                                })
                                ->where(function ($q){
                                    $q->where('father_id',null)->orwhere('father_id',0);
                                })
                                ->when($this->family_id,function ($q){

                                    $q->where(function ( $query) {
                                        $query->where('mother_id', null)
                                            ->orwhere('mother_id', 0)
                                            ->orwhereNotIn('mother_id',$this->mother);
                                    });
                                });
                        })
                        ->orderBy('familyshow_id')
                        ->orderBy('family_id')
                        ->orderBy('masterKey');
            })
            ->paginationPageOptions([5,10,12,25,50,100])
            ->columns([
                    ImageColumn::make('image2')
                        ->height(160)
                        ->action(
                            Action::make('Upload')
                                ->fillForm(function (Victim $record){
                                    return ['image2'=>$record->image2];
                                })
                                ->form([
                                    FileUpload::make('image2')
                                        ->multiple()
                                        ->imageEditor()
                                        ->directory('form-attachments'),
                                ])
                                ->action(function (array $data,Victim $record,){
                                    $record->update(['image2'=>$data['image2'], ]);
                                })
                        )
                        ->limit(1)
                        ->circular(),
                Stack::make([
                    Panel::make([
                        TextColumn::make('FullName')
                            ->label('الاسم')
                            ->formatStateUsing(fn (Victim $record): View => view(
                                'filament.user.pages.name-only2',
                                ['record' => $record],
                            ))
                            ->searchable()
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->sortable(),
                        TextColumn::make('Name2')
                            ->formatStateUsing(fn (Victim $record): HtmlString =>
                            new HtmlString('<span class="text-lg">'.$record->year.'&nbsp;&nbsp;&nbsp; - '.$record->Street->StrName.'</span>')),
                        TextColumn::make('Name1')
                            ->formatStateUsing(fn (Victim $record): View => view(
                                'filament.user.pages.view-marry',
                                ['record' => $record],
                            )),
                        TextColumn::make('Name3')
                            ->formatStateUsing(fn (Victim $record): View => view(
                                'filament.user.pages.view-father',
                                ['record' => $record],
                            )),
                        TextColumn::make('Name4')
                            ->formatStateUsing(fn (Victim $record): View => view(
                                'filament.user.pages.view-mother',
                                ['record' => $record],
                            )),

                        TextColumn::make('is_mother')
                            ->formatStateUsing(fn (Victim $record): View => view(
                                'filament.user.pages.view-sons2',
                                ['record' => $record],
                            )),

                    ]),




                ]),

            ])
        ->contentGrid([
        'md' => 2,
        'xl' => 3,
    ]);
    }
}
