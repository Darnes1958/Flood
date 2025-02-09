<?php

namespace App\Filament\User\Pages;

use App\Enums\jobType;
use App\Enums\qualyType;
use App\Enums\talentType;
use App\Filament\Resources\VictimResource;
use App\Filament\User\Resources\VictimResource\Pages\ListVictims;
use App\Livewire\Traits\PublicTrait;
use App\Models\Archif;
use App\Models\Bait;
use App\Models\Balag;
use App\Models\Bedon;
use App\Models\BigFamily;
use App\Models\Dead;
use App\Models\Family;
use App\Models\Familyshow;
use App\Models\Familyshow_count;
use App\Models\Job;
use App\Models\Mafkoden;
use App\Models\Qualification;
use App\Models\Road;
use App\Models\Street;
use App\Models\Talent;
use App\Models\Tarkeba;
use App\Models\Tasreeh;
use App\Models\VicTalent;
use App\Models\Victim;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class UserInfoPage extends Page implements HasTable,HasForms
{
  use InteractsWithTable,InteractsWithForms;
    use PublicTrait;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.user-info-page';

  protected ?string $heading='';
  protected static ?string $navigationLabel='استفسار وبحث';
  protected static ?int $navigationSort=1;


  public $family_id=null;
  public $familyshow_id;

  public $street_id=null;
  public $show='all';
  public $mother;
  public $count;
  public $notes=true;
  public $hasNotes=false;
  static $ser=0;

  #[On('resetInfo')]
  public function resetInfo()
  {
      $this->resetTable();
  }

  public  function Do($id)
  {
      $this->dispatch('fillModal', id: $id);
      $this->dispatch('open-modal', id: 'talentModal');
  }
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
                  \Filament\Forms\Components\Actions\Action::make('printAll')
                      ->label('طباعة الكل')
                      ->color('success')
                      ->icon('heroicon-m-printer')
                      ->action(function (Get $get){

                          $familyshow_id=$get('familyshow_id');
                          $familyshow=Familyshow::find($familyshow_id);

                          $families=Family::where('familyshow_id',$familyshow_id)->pluck('id')->all();

                          $fam=Family::whereIn('id',$families)->get();

                          $count=Victim::whereIn('family_id',$families)->count();

                          $victim_father=Victim::with('father')
                              ->whereIn('family_id',$families)
                              ->where('is_father','1')->get();

                          $fathers=Victim::whereIn('family_id',$families)->where('is_father',1)->select('id');
                          $mothers=Victim::whereIn('family_id',$families)->where('is_mother',1)->select('id');

                          $victim_mother=Victim::with('mother')
                              ->whereIn('family_id',$families)
                              ->where('is_mother','1')
                              ->where(function ( $query) use($fathers) {
                                  $query->where('husband_id', null)
                                      ->orwhere('husband_id', 0)
                                      ->orwhereNotIn('husband_id',$fathers);
                              })

                              ->get();

                          $victim_husband=Victim::
                          whereIn('family_id',$families)
                              ->where('male','ذكر')
                              ->where('is_father','0')
                              ->where('wife_id','!=',null)
                              ->get();

                          $victim_wife=Victim::
                          whereIn('family_id',$families)

                              ->where('male','أنثي')
                              ->where('is_mother','0')
                              ->where('husband_id','!=',null)
                              ->get();
                          $victim_only=Victim::
                          whereIn('family_id',$families)

                              ->Where(function ( $query) {
                                  $query->where('is_father',null)
                                      ->orwhere('is_father',0);
                              })
                              ->Where(function ( $query) {
                                  $query->where('is_mother',null)
                                      ->orwhere('is_mother',0);
                              })
                              ->where('husband_id',null)
                              ->where('wife_id',null)
                              ->where('father_id',null)
                              ->where(function ( $query) use($mothers) {
                                  $query->where('mother_id', null)
                                      ->orwhere('mother_id', 0)
                                      ->orwhereNotIn('mother_id',$mothers);
                              })
                              ->get();
                          \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfAllVictims',
                              ['fam'=>$fam,
                                  'victim_father'=>$victim_father,
                                  'victim_mother'=>$victim_mother,
                                  'victim_husband'=>$victim_husband,
                                  'victim_wife'=>$victim_wife,
                                  'victim_only'=>$victim_only,
                                  'count'=>$count,
                                  'familyshow'=>$familyshow,])
                              ->save(public_path().'/bigFamily.pdf');

                          return Response::download(public_path().'/bigFamily.pdf',
                              'filename.pdf', self::ret_spatie_header());

                      }),
                  \Filament\Forms\Components\Actions\Action::make('printAll2')
                      ->label('طباعة كل الضحايا')
                      ->color('success')
                      ->icon('heroicon-m-printer')
                      ->action(function (Get $get){


                          $familyshowAll=Familyshow_count::where('nation','ليبيا')->orderBy('count','desc')->get();


                          \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfAllVictims_2',
                              [
                                  'familyshowAll'=>$familyshowAll,])
                              ->save(public_path().'/bigFamily.pdf');

                          return Response::download(public_path().'/bigFamily.pdf',
                              'filename.pdf', self::ret_spatie_header());

                      }),
                  \Filament\Forms\Components\Actions\Action::make('printInfo')
                      ->label('طباعة الاحصائيات')
                      ->color('success')
                      ->icon('heroicon-m-printer')
                      ->action(function (Get $get){


                          $west=Street::whereIn('road_id',Road::where('east_west','غرب الوادي')->pluck('id'))->pluck('id');
                          $east=Street::whereIn('road_id',Road::where('east_west','شرق الوادي')->pluck('id'))->pluck('id');


                          \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfInfo',
                              [
                                  'count'=>Victim::count(),
                                  'libyan'=>Victim::whereIn('family_id',Family::where('country_id',1)->pluck('id'))->count(),
                                  'forign'=>Victim::whereIn('family_id',Family::where('country_id','!=',1)->pluck('id'))->count(),
                                  'male'=>Victim::where('male','ذكر')->count(),
                                  'female'=>Victim::where('male','أنثي')->count(),
                                  'father'=>Victim::where('is_father',1)->count(),
                                  'mother'=>Victim::where('is_mother',1)->count(),
                                  'forignWives'=>Victim::whereIn('family_id',[303,306,10384,10404])->count(),
                                  'forignHusband'=>Victim::where('husband_id','!=',null)
                                      ->whereNotIn('family_id',[120,162,207,250,303,306,308,343,344,345,346,347,10375,10376,10377,10384])
                                      ->whereIn('husband_id',Victim::whereIn('family_id',[120,162,207,250,303,306,308,343,344,345,346,347,10375,10376,10377,10384])->where('wife_id','!=',null)->pluck('id'))
                                      ->count(),
                                  'in_work'=>Victim::where('inWork',1)->count(),
                                  'at_save'=>Victim::where('inSave',1)->count(),
                                  'guest'=>Victim::where('guests',1)->count(),
                                  'east'=>Victim::whereIn('street_id',$east)->count(),
                                  'west'=>Victim::whereIn('street_id',$west)->count(),
                                  'derna'=>Victim::whereIn('street_id',Street::where('road_id',15)->pluck('id'))->count(),
                                  'naga'=>Victim::whereIn('street_id',Street::where('road_id',16)->pluck('id'))->count(),

                                  ])
                              ->save(public_path().'/bigFamily.pdf');

                          return Response::download(public_path().'/bigFamily.pdf',
                              'filename.pdf', self::ret_spatie_header());

                      }),



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
                ->orderBy('family_id');
      })
        ->paginationPageOptions([5,10,25,50,100])
      ->columns([

              TextColumn::make('FullName')
                  ->label('الاسم بالكامل')
                  ->sortable()
                  ->searchable()
                  ->description(function (Victim $record){
                      $who='';
                      if (!$record->sonOfMother) {

                          $bed = null;
                          $maf = null;
                          $ded = null;
                          $bal = null;
                          if ($record->balag) $bal = Balag::find($record->balag);
                          if ($record->dead) $ded = Dead::find($record->dead);
                          if ($record->bedon) $bed = Bedon::find($record->bedon);
                          if ($record->mafkoden) $maf = Mafkoden::find($record->mafkoden);

                          if ($bed || $maf || $ded || $bal) {
                              if ($bal && $bal->mother) $who = 'اسم الأم : ' . $bal->mother;
                              if ($who=='' && $ded && $ded->mother) $who = 'اسم الأم : ' . $ded->mother;
                              if ($who=='' && $bed && $bed->mother) $who = 'اسم الأم : ' . $bed->mother;
                              if ($who=='' && $maf && $maf->mother) $who = $who . 'اسم الأم : ' . $maf->mother;

                          }
                      }
                      if ($record->notes) $who=$who.' ('.$record->notes.')';

                      return $who;
                  })

                  ->formatStateUsing(fn (Victim $record): View => view(
                      'filament.user.pages.full-data',
                      ['record' => $record],
                  ))
                  ->searchable(),
          TextColumn::make('year')
              ->label('مواليد')
          ,
              TextColumn::make('Familyshow.name')
                  ->label('العائلة')
                  ->sortable()
                  ->toggleable()
                  ->hidden(function (){return $this->familyshow_id !=null;})
                  ->searchable(),
              TextColumn::make('Family.FamName')
                  ->label('التسمية')
                  ->sortable()
                  ->toggleable()
                  ->visible(function (){
                      return $this->familyshow_id && Family::where('familyshow_id',$this->familyshow_id)->count()>1;
                  })
                  ->searchable(),

              TextColumn::make('Street.StrName')
                  ->label('العنوان')
                  ->action(
                      Action::make('updateٍStreet')
                          ->form([
                              Select::make('street_id')
                                  ->options(Street::all()->pluck('StrName','id'))
                                  ->label('العنوان')
                                  ->searchable()
                                  ->preload()
                                  ->live()
                          ])
                          ->fillForm(fn (Victim $record): array => [
                              'street_id' => $record->street_id,
                          ])
                          ->visible(Auth::user()->is_admin)
                          ->modalCancelActionLabel('عودة')
                          ->modalSubmitActionLabel('تحزين')
                          ->modalHeading('تعديل العنوان')
                          ->action(function (array $data,Victim $record,){
                              $record->update(['street_id'=>$data['street_id']]);
                              Victim::where('father_id',$record->id)
                                  ->orwhere('id',$record->mother_id)
                                  ->orwhere('id',$record->father_id)
                                  ->orwhere('husband_id',$record->id)
                                  ->orwhere('wife_id',$record->id)
                                  ->orwhere('mother_id',$record->id)
                                  ->update(['street_id'=>$data['street_id']]);
                          })
                  )
                  ->toggleable()
                  ->sortable()
                  ->searchable(),
              TextColumn::make('Qualification.name')
                          ->label('المؤهل')
                          ->action(
                              Action::make('updateQualification')
                                  ->form([
                                      Select::make('qualification_id')
                                          ->options(Qualification::all()->pluck('name','id'))
                                          ->label('المؤهل')
                                          ->createOptionForm([
                                              TextInput::make('name')
                                                  ->required()
                                                  ->label('المؤهل')
                                                  ->maxLength(255),
                                              Select::make('qualyType')
                                                  ->label('التصنيف')
                                                  ->searchable()
                                                  ->options(qualyType::class)
                                          ])
                                          ->createOptionUsing(function (array $data): int {
                                              return Qualification::create($data)->getKey();
                                          })
                                          ->fillEditOptionActionFormUsing(function (Victim $record){
                                              $q=Qualification::find($record->id);
                                              if ($q)
                                              return Qualification::find($record->id)->toArray();  else return [];
                                          })
                                          ->editOptionForm([
                                              TextInput::make('name')
                                                  ->label('المؤهل')
                                                  ->maxLength(255),
                                              Select::make('qualyType')
                                                  ->label('التصنيف')
                                                  ->searchable()
                                                  ->options(qualyType::class)
                                          ])
                                          ->searchable()
                                          ->preload()
                                          ->live()
                                  ])
                                  ->fillForm(fn (Victim $record): array => [
                                      'qualification_id' => $record->qualification_id,
                                  ])
                                  ->modalCancelActionLabel('عودة')
                                  ->modalSubmitActionLabel('تحزين')
                                  ->modalHeading('تعديل المؤهل')
                                  ->action(function (array $data,Victim $record,){
                                      $record->update(['qualification_id'=>$data['qualification_id']]);
                                  })
                          )
                          ->toggleable(),
              TextColumn::make('Job.name')
                          ->formatStateUsing(fn (Victim $record): View => view(
                              'filament.user.pages.job-data',
                              ['record' => $record],
                          ))
                          ->label('المهنة')
                          ->action(
                              Action::make('updateJob')
                                  ->form([
                                      Select::make('job_id')
                                          ->options(Job::all()->pluck('name','id'))
                                          ->label('المهنة')
                                          ->createOptionForm([
                                              TextInput::make('name')
                                                  ->required()
                                                  ->label('الوظيفة')
                                                  ->maxLength(255),
                                              Select::make('jobType')
                                                  ->label('التصنيف')
                                                  ->searchable()
                                                  ->options(jobType::class)
                                          ])
                                          ->createOptionUsing(function (array $data): int {
                                              return Job::create($data)->getKey();
                                          })
                                          ->fillEditOptionActionFormUsing(function (Victim $record){
                                              $job=Job::find($record->id);
                                              if ($job)
                                              return $job->toArray(); else return [];
                                          })
                                          ->editOptionForm([
                                              TextInput::make('name')
                                                  ->required()
                                                  ->label('الوظيفة')
                                                  ->maxLength(255),
                                              Select::make('jobType')
                                                  ->label('التصنيف')
                                                  ->searchable()
                                                  ->options(jobType::class)
                                          ])
                                          ->searchable()
                                          ->preload()
                                          ->live()
                                  ])
                                  ->fillForm(fn (Victim $record): array => [
                                      'job_id' => $record->job_id,
                                  ])
                                  ->modalCancelActionLabel('عودة')
                                  ->modalSubmitActionLabel('تحزين')
                                  ->modalHeading('تعديل المهنة')
                                  ->action(function (array $data,Victim $record,){
                                      $record->update(['job_id'=>$data['job_id']]);
                                  })
                          )
                          ->toggleable(),
          TextColumn::make('VicTalent.Talent.name')
              ->label('المواهب')
              ->formatStateUsing(fn (Victim $record): View => view(
                  'filament.user.pages.talent-data',
                  ['record' => $record],
              ))

              ->action(function (Victim $record){$this->Do($record->id);})
              ->toggleable(),
              ImageColumn::make('image2')
                  ->toggleable()
                  ->stacked()
                  ->placeholder('الصورة')
                  ->tooltip('اضغط للإدخال او التعديل')
                  ->action(
                      Action::make('Upload')
                          ->fillForm(function (Victim $record){
                              return ['image2'=>$record->image2];
                          })
                          ->form([
                              FileUpload::make('image2')
                                  ->multiple()
                                  ->directory('form-attachments'),
                          ])
                          ->action(function (array $data,Victim $record,){
                              $record->update(['image2'=>$data['image2'], ]);
                          })
                  )
                  ->label('')
                  ->circular(),
      ])

      ->actions([
        Action::make('View Information')
          ->iconButton()
          ->modalHeading('')
          ->modalWidth(MaxWidth::SevenExtraLarge)
          ->icon('heroicon-s-eye')
          ->modalSubmitAction(false)
          ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة'))
          ->infolist([
            \Filament\Infolists\Components\Section::make()
              ->schema([
                \Filament\Infolists\Components\Section::make()
                  ->schema([
                    TextEntry::make('FullName')
                      ->color(function (Victim $record){
                        if ($record->male=='ذكر') return 'primary';  else return 'Fuchsia';})
                      ->columnSpan(3)
                      ->weight(FontWeight::ExtraBold)
                      ->size(TextEntry\TextEntrySize::Large)
                      ->label(''),
                      TextEntry::make('year')
                          ->visible(function (Victim $record){return $record->year!=null;})
                          ->inlineLabel()
                          ->color('rose')
                          ->label(new HtmlString('<span style="color: yellow">مواليد</span>')),
                    TextEntry::make('sonOfFather.FullName')
                      ->visible(function (Victim $record){
                        return $record->father_id;
                      })
                      ->color('info')
                      ->label('والده')
                      ->size(TextEntry\TextEntrySize::Large)

                      ->columnSpanFull(),
                    TextEntry::make('sonOfMother.FullName')
                      ->visible(function (Victim $record){
                        return $record->mother_id;
                      })
                      ->color('Fuchsia')
                      ->label('والدته')
                      ->size(TextEntry\TextEntrySize::Large)

                      ->columnSpanFull(),

                    TextEntry::make('husband.FullName')
                      ->visible(function (Victim $record){
                        return $record->wife_id;
                      })
                      ->color('Fuchsia')
                      ->label('زوجته')
                      ->size(TextEntry\TextEntrySize::Large)
                      ->separator(',')
                      ->columnSpanFull(),
                    TextEntry::make('husband2.FullName')
                      ->visible(function (Victim $record){
                        return $record->wife2_id;
                      })
                      ->color('Fuchsia')
                      ->label('زوجته الثانية')
                      ->size(TextEntry\TextEntrySize::Large)
                      ->columnSpanFull(),
                    TextEntry::make('wife.FullName')
                      ->visible(function (Victim $record){
                        return $record->husband_id;
                      })
                      ->label('زوجها')
                      ->badge()
                      ->separator(',')
                      ->columnSpanFull(),

                    TextEntry::make('father.Name1')
                      ->visible(function (Victim $record){
                        return $record->is_father;
                      })
                      ->label('أبناءه')
                      ->color(function( )  {
                        self::$ser++;

                        switch (self::$ser){
                          case 1: $c='success';break;
                          case 2: $c='info';break;
                          case 3: $c='yellow';break;
                          case 4: $c='rose';break;
                          case 5: $c='blue';break;
                          case 6: $c='Fuchsia';break;
                          default: $c='primary';break;
                        }
                        return $c;

                      })
                      ->badge()
                      ->separator(',')
                      ->columnSpanFull(),
                    TextEntry::make('mother.Name1')
                      ->visible(function (Victim $record){
                        return $record->is_mother;
                      })
                      ->label('أبناءها')
                      ->badge()
                      ->separator(',')
                      ->columnSpanFull(),
                      TextEntry::make('Familyshow.name')
                          ->color('info')
                          ->label('العائلة'),
                    TextEntry::make('Family.FamName')
                        ->visible(function (){
                            return $this->familyshow_id && Family::where('familyshow_id',$this->familyshow_id)->count()>1;
                        })
                      ->color('info')
                      ->label('التسمية'),
                    TextEntry::make('Family.Tribe.TriName')
                      ->color('info')
                      ->label('القبيلة'),
                    TextEntry::make('Street.StrName')
                      ->color('info')
                      ->label('العنوان'),
                    TextEntry::make('Street.Area.AreaName')
                      ->color('info')
                      ->label('المحلة'),

                    TextEntry::make('Qualification.name')
                      ->visible(function (Model $record){
                        return $record->qualification_id;
                      })
                      ->color('info')
                      ->label('المؤهل'),
                    TextEntry::make('Job.name')
                      ->visible(function (Model $record){
                        return $record->job_id;
                      })
                      ->color('info')
                      ->label('الوظيفة'),
                    TextEntry::make('VicTalent.Talent.name')
                      ->visible(function (Model $record){
                        return VicTalent::where('victim_id',$record->id)->exists() ;
                      })

                      ->color('info')
                      ->label('المواهب'),
                    TextEntry::make('notes')
                      ->label('')

                  ])
                  ->columns(4)
                  ->columnSpan(2),

                ImageEntry::make('image2')
                  ->label('')
                    ->stacked()
                    ->label('')
                    ->height(500)
                  ->columnSpan(2)


              ])->columns(4)
          ])
          ->slideOver(),

      ])
      ;
  }
}
