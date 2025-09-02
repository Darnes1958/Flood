<?php

namespace App\Filament\User\Pages;

use App\Enums\jobType;
use App\Enums\qualyType;
use App\Livewire\Traits\PublicTrait;
use App\Models\Balag;
use App\Models\Bedon;
use App\Models\Dead;
use App\Models\Egypt;
use App\Models\Family;
use App\Models\Familyshow;
use App\Models\Familyshow_count;
use App\Models\Job;
use App\Models\Mafkoden;
use App\Models\Qualification;
use App\Models\Road;
use App\Models\Street;
use App\Models\VicTalent;
use App\Models\Victim;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class EgyptPage extends Page implements HasTable,HasForms
{
  use InteractsWithTable,InteractsWithForms;
    use PublicTrait;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.egypt-page';

  protected ?string $heading='';
  protected static ?string $navigationLabel='صفحة المصريين';
  protected static ?int $navigationSort=1;


  public $street_id=null;
  public $show='all';
  public $mother;
  public $count;
  public $notes=true;
  public $hasNotes=false;
  public $trusted_type=1;
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
              Radio::make('trusted_type')
               ->hiddenLabel()
               ->inline()
               ->inlineLabel(false)
               ->columnSpan(2)
               ->options([
                   1=>'موثوقين',
                   2=>'غير موثوقين',
                   3=>'الكل',
               ])
               ->live()
               ->afterStateUpdated(fn ($state) => $this->trusted_type=$state),



              \Filament\Forms\Components\Actions::make([
                  \Filament\Forms\Components\Actions\Action::make('printBigFamily')
                      ->label('طباعة ')

                      ->color('success')
                      ->icon('heroicon-m-printer')
                      ->action(function (Get $get){

                          \Spatie\LaravelPdf\Facades\Pdf::view('PDF.PdfEgypt',
                              [
                                  'victims' => $this->getTableQueryForExport()->get(),])
                              ->footerView('PDF.footer')
                              ->save(public_path().'/bigFamily.pdf');

                          return Response::download(public_path().'/bigFamily.pdf',
                              'filename.pdf', self::ret_spatie_header());

                      }),

              ])->columns(3)->columnSpan(2),

          ])
          ->columns(9),

      ]);
  }

  public function table(Table $table): Table
  {
    return $table

      ->query(function (){
          $trustedList=Egypt::where('published',1)->pluck('victim_id');
        return
            Victim::query()
                ->where('familyshow_id' ,181)
                ->when($this->trusted_type==1,function($query) use ($trustedList){
                    $query->whereIn('id',$trustedList);
                })
                ->when($this->trusted_type==2,function($query) use ($trustedList){
                    $query->whereNotIn('id',$trustedList);
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

                ->orderBy('masterKey')->orderBy('Name1');
      })
        ->paginationPageOptions([5,10,25,50,100])
        ->searchPlaceholder('بحث  ')
        ->searchDebounce('750ms')
      ->columns([
              TextColumn::make('Name1')
                  ->label('الإسم الأول')
                  ->searchable(isIndividual: true),
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
              ->label('مواليد') ,



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
                  ,

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

              IconColumn::make('trusted')
               ->state(function (Victim $record) {
                   return  $record->Egypt->published;
               })
               ->action(
                   Action::make('upd_trust')
                    ->fillForm(fn (Victim $record): array => ['published'=>$record->Egypt->published,'why'=>$record->Egypt->why,'notes'=>$record->Egypt->notes])
                    ->form([
                        Radio::make('published')
                         ->label('موثوق')
                         ->boolean(),
                        TextInput::make('why')
                         ->label('لماذا موثوق'),
                        TextInput::make('notes')
                         ->label('ملاحظات')
                    ])
                   ->action(function (array $data,Victim $record,){
                       $record->Egypt->update(['notes'=>$data['notes'],'why'=>$data['why'],'published'=>$data['published']]);
                   })
               )
               ->boolean()
               ->label('موثوق'),

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
                                  ->imageEditor()
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
