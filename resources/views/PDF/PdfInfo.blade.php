@extends('PDF.PrnMaster3')

@section('mainrep')


    <div class="flex flex-row  justify-center items-center mt-40" >
        <label class="text-2xl  m-4"> العدد الكلي </label>
        <label  class="text-2xl text-red-500  font-bold"> {{$count}} </label>
    </div>
    <div class="flex flex-row mt-10 mr-40">
        <div class="basis-1/2 ">
            <label class="text-2xl m-2 ">ليبيين  : </label>
            <label  class="text-2xl text-red-600"> {{$libyan}} </label>
        </div>
        <div class="basis-1/2 ">
            <label class="text-2xl m-2">أجانب  : </label>
            <label  class="text-2xl text-red-600"> {{$forign}} </label>
        </div>
    </div>
    <div class="flex flex-row mt-10 mr-40">
        <div class="basis-1/2 ">
            <label class="text-2xl  m-2 ">ذكور  : </label>
            <label  class="text-2xl text-red-600"> {{$male}} </label>
        </div>
        <div class="basis-1/2 ">
            <label class="text-2xl m-2">إناث  : </label>
            <label  class="text-2xl text-red-600"> {{$female}} </label>
        </div>
    </div>
    <div class="flex flex-row mt-10 mr-40">
        <div class="basis-1/2 ">
            <label class="text-2xl  m-2 ">جد  : </label>
            <label  class="text-2xl text-red-600"> {{\App\Models\Victim::where('is_grandfather',1)->count()}} </label>
        </div>
        <div class="basis-1/2 ">
            <label class="text-2xl m-2">جده  : </label>
            <label  class="text-2xl text-red-600"> {{\App\Models\Victim::where('is_grandmother',1)->count()}} </label>
        </div>
    </div>
    <div class="flex flex-row mt-10 mr-40">
        <div class="basis-1/2 ">
            <label class="text-2xl  m-2 ">أباء  : </label>
            <label  class="text-2xl text-red-600"> {{$father}} </label>
        </div>
        <div class="basis-1/2 ">
            <label class="text-2xl m-2">أمهات  : </label>
            <label  class="text-2xl text-red-600"> {{$mother}} </label>
        </div>
    </div>
    <div class="flex flex-row mt-10 mr-20">
        <div class="basis-1/2 ">
            <label class="text-2xl  m-2 ">زوجات أجنبيات  : </label>
            <label  class="text-2xl text-red-600"> {{$forignWives}} </label>
        </div>
        <div class="basis-1/2 ">
            <label class="text-2xl m-2">متزوجات من أجانب  : </label>
            <label  class="text-2xl text-red-600"> {{$forignHusband}} </label>
        </div>
    </div>

    @pageBreak

    @php($country=\App\Models\Country::all())
    <div class="flex flex-row  justify-center items-center bg-zinc-50 " >
        <table style="width: 50%;margin-top: 40px;"  >
            @foreach($country as $c)
                <tr class="h-14 ">

                    <td class="text-xl border-0">
                        <div class="flex  ">
                            <div >
                                <x-filament::avatar
                                    src="{{ storage_path('app/public/'.$c->image) }}"
                                    size="sm"
                                />

                            </div>
                            <label>&nbsp;&nbsp;</label>
                            {{$c->name}}

                        </div>
                    </td>
                    <td class="text-xl border-0">  {{$c->Victim->count()}}</td>

                </tr>
            @endforeach

        </table>
    </div>
    @pageBreak
    @php($familyshow=\App\Models\Familyshow_count::where('country_id',1)->orderBy('count','desc')->get())
    @php($i=1)
    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 96%;margin-top: 40px;"  >
            <thead>
            <tr class="h-10">
                <td class="bg-blue-300 w-30 text-lg text-center">ت</td>
                <td class="bg-blue-300 w-30 text-lg text-center">العائلة</td>
                <td class="bg-blue-300 w-20 text-lg text-center">العدد</td>
                <td class="bg-blue-300 w-30 text-lg text-center">ت</td>
                <td class="bg-blue-300 w-30 text-lg text-center">العائلة</td>
                <td class="bg-blue-300 w-20 text-lg text-center">العدد</td>
                <td class="bg-blue-300 w-30 text-lg text-center">ت</td>
                <td class="bg-blue-300 w-30 text-lg text-center">العائلة</td>
                <td class="bg-blue-300 w-20 text-lg text-center">العدد</td>
            </tr>
            </thead>

            @foreach($familyshow->split($familyshow->count()/3) as $row)
                <tr class="h-8 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                    @foreach($row as $c)
                        <td class="text-sm text-center">{{$i}}</td>
                        <td class="text-sm ">{{$c->name}}</td>
                        <td class="text-sm text-center">{{$c->count}}</td>
                        @php($i++)
                    @endforeach
                </tr>
            @endforeach


        </table>
    </div>
    @pageBreak
    <div class="flex flex-row  justify-center items-center mt-40" >
        <label class="text-2xl  m-4"> العدد حسب المحلات </label>
    </div>
    @php($data=\App\Models\Area::query()->get())
    @php($i=1)
    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 96%;margin-top: 40px;"  >
            <thead>
            <tr class="h-10">

                <td class="bg-blue-300 w-30 text-lg text-center">الاسم</td>
                <td class="bg-blue-300 w-20 text-lg text-center">العدد</td>

                <td class="bg-blue-300 w-30 text-lg text-center">الاسم</td>
                <td class="bg-blue-300 w-20 text-lg text-center">العدد</td>

            </tr>
            </thead>

            @foreach($data->split($data->count()/2) as $row)
                <tr class="h-8 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                    @foreach($row as $rec)
                        <td class="text-sm ">{{$rec->AreaName}}</td>
                        <td class="text-sm text-center">{{$rec->Victim->count()}}</td>
                    @endforeach
                </tr>
            @endforeach


        </table>
    </div>
    @pageBreak
    <div class="flex flex-row  justify-center items-center mt-40" >
        <label class="text-2xl  m-4"> العدد حسب الشوارع الرئيسية </label>
    </div>
    @php($data=\App\Models\Road::query()->get())
    @php($i=1)
    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 96%;margin-top: 40px;"  >
            <thead>
            <tr class="h-10">

                <td class="bg-blue-300 w-30 text-lg text-center">الاسم</td>
                <td class="bg-blue-300 w-20 text-lg text-center">العدد</td>

                <td class="bg-blue-300 w-30 text-lg text-center">الاسم</td>
                <td class="bg-blue-300 w-20 text-lg text-center">العدد</td>

            </tr>
            </thead>

            @foreach($data->split($data->count()/2) as $row)
                <tr class="h-8 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                    @foreach($row as $rec)
                        <td class="text-sm ">{{$rec->name}}</td>
                        <td class="text-sm text-center">{{$rec->Victim->count()}}</td>
                    @endforeach
                </tr>
            @endforeach


        </table>
    </div>
    <div class="flex flex-row mt-10 mr-20">
        <div class="basis-1/2 ">
            <label class="text-2xl  m-2 ">غرب الوادي  : </label>
            <label  class="text-2xl text-red-600"> {{$west}} </label>
        </div>
        <div class="basis-1/2 ">
            <label class="text-2xl m-2">شرق الوادي  : </label>
            <label  class="text-2xl text-red-600"> {{$east}} </label>
        </div>
    </div>
    @pageBreak
    <div class="flex flex-row  justify-center items-center mt-20" >
        <label class="text-2xl  m-4"> العدد حسب المواليد </label>

    </div>
    @php($data=\App\Models\Year::query()->get())

    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 96%;margin-top: 40px;"  >
            <thead>
            <tr class="h-10">

                <td style="width: 40%" class="bg-blue-300  text-lg text-center">الفترة</td>
                <td style="width: 50%" class="bg-blue-300  text-lg text-center">العدد</td>

            </tr>
            </thead>

            @foreach($data as $key=> $row)
                <tr class="h-8 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                   <td class="text-lg text-center">{{$row->name}}</td>
                    <td class="text-lg text-center">{{$row->count}}</td>
                </tr>
            @endforeach
            <br>





        </table>
    </div>
    <br>
    <div>
        <x-filament::avatar
            src="{{ public_path('img/years1.jpg') }}"
            size="xl"
            :circular="false"
        />
    </div>

    @pageBreak
    @php($data=\App\Models\Categorie::query()->get())
    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 96%;margin-top: 40px;"  >
            <thead>
            <tr class="h-10">

                <td style="width: 40%" class="bg-blue-300  text-lg text-center">الفترة</td>
                <td style="width: 50%" class="bg-blue-300  text-lg text-center">العدد</td>

            </tr>
            </thead>

            @foreach($data as $key=> $row)
                <tr class="h-8 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                    <td class="text-lg text-center">{{$row->name}}</td>
                    <td class="text-lg text-center">{{$row->count}}</td>
                </tr>
            @endforeach

        </table>
    </div>
    <br>
    <div>
        <x-filament::avatar
            src="{{ public_path('img/years2.jpg') }}"
            size="xl"
            :circular="false"
        />
    </div>
    <br>
    <div class="flex mr-10">
        <p class="text-red-300">
            ملاحظة :
        </p>
        <p class="text-blue-500">
            لم تتوفر بيانات المواليد لكل الضحايا (تم ادخال 75%)
        </p>

    </div>
    @pageBreak

    <div class="flex flex-row  justify-center items-center mt-40" >
        <label class="text-2xl  m-4"> وفاة أثناء العمل </label>
        <label  class="text-2xl text-red-600"> {{\App\Models\Victim::where('inWork',1)->count()}} </label>
    </div>
    @php($data=\App\Models\Victim::where('inWork',1)->get())

    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 96%;margin-top: 40px;"  >
            <thead>
            <tr class="h-10">
                <td style="width: 6%" class="bg-blue-300  text-lg text-center">ت</td>

                <td style="width: 30%" class="bg-blue-300  text-lg text-center">الاسم</td>
                <td style="width: 30%" class="bg-blue-300  text-lg text-center">العنوان</td>
                <td style="width: 34%" class="bg-blue-300  text-lg text-center">ملاحظات</td>
            </tr>
            </thead>

            @foreach($data as $key=> $row)
                <tr class="h-8 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                    <td class="text-sm text-center">{{$key+1}}</td>
                    @if(!$row->image)
                        <td class="text-sm ">{{$row->FullName}}</td>
                    @else
                        <td class="text-sm ">
                            <div class="flex">
                                {{$row->FullName}}
                                <div>
                                    <x-filament::avatar
                                        src="{{ storage_path('app/public/'.$row->image) }}"
                                        size="sm"
                                    />
                                </div>
                            </div>
                        </td>
                    @endif

                    <td class="text-xs ">{{$row->Street->StrName}}</td>
                    <td class="text-xs ">{{$row->notes}}</td>

                </tr>
            @endforeach


        </table>
    </div>
    @pageBreak

    <div class="flex flex-row  justify-center items-center mt-40" >
        <label class="text-2xl  m-4"> وفاة أثناء الإنقاذ </label>
        <label  class="text-2xl text-red-600"> {{\App\Models\Victim::where('inSave',1)->count()}} </label>
    </div>
    @php($data=\App\Models\Victim::where('inSave',1)->get())

    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 96%;margin-top: 40px;"  >
            <thead>
            <tr class="h-10">
                <td style="width: 6%" class="bg-blue-300  text-lg text-center">ت</td>
                <td style="width: 40%" class="bg-blue-300  text-lg text-center">الاسم</td>
                <td style="width: 50%" class="bg-blue-300  text-lg text-center">ملاحظات</td>

            </tr>
            </thead>

            @foreach($data as $key=> $row)
                <tr class="h-8 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                    <td class="text-sm text-center">{{$key+1}}</td>
                    @if(!$row->image)
                        <td class="text-sm ">{{$row->FullName}}</td>
                    @else
                        <td class="text-sm ">
                            <div class="flex">
                                {{$row->FullName}}
                                <div>
                                    <x-filament::avatar
                                        src="{{ storage_path('app/public/'.$row->image) }}"
                                        size="sm"
                                    />
                                </div>
                            </div>
                        </td>
                    @endif
                    <td class="text-xs ">{{$row->notes}}</td>



                </tr>
            @endforeach


        </table>
    </div>
    <br>
    <div class="flex mr-10">
        <p class="text-red-300">
            ملاحظة :
        </p>
        <p class="text-blue-500">
هذا ما توفر لدينا من بيانات , وقد تكون هناك اسماء اخري توفيت بسبب الإنقاذ
        </p>

    </div>
    @pageBreak
    <div class="flex flex-row  justify-center items-center mt-40" >
        <label class="text-2xl  m-4"> ضيوف من خارج المدينة او من مناطق أمنة </label>
        <label  class="text-2xl text-red-600"> {{\App\Models\Victim::where('guests',1)->count()}} </label>
    </div>
    @php($data=\App\Models\Victim::where('guests',1)->get())

    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 96%;margin-top: 40px;"  >
            <thead>
            <tr class="h-10">
                <td style="width: 6%" class="bg-blue-300  text-lg text-center">ت</td>
                <td style="width: 40%" class="bg-blue-300  text-lg text-center">الاسم</td>
                <td style="width: 50%" class="bg-blue-300  text-lg text-center">ملاحظات</td>

            </tr>
            </thead>

            @foreach($data as $key=> $row)
                <tr class="h-8 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                    <td class="text-sm text-center">{{$key+1}}</td>
                    @if(!$row->image)
                        <td class="text-sm ">{{$row->FullName}}</td>
                    @else
                        <td class="text-sm ">
                            <div class="flex">
                                {{$row->FullName}}
                                <div>
                                    <x-filament::avatar
                                        src="{{ storage_path('app/public/'.$row->image) }}"
                                        size="sm"
                                    />
                                </div>
                            </div>
                        </td>
                    @endif
                    <td class="text-xs ">{{$row->notes}}</td>



                </tr>
            @endforeach


        </table>
    </div>
    @pageBreak


    <div class="flex flex-row  justify-center items-center mt-40" >
        <label class="text-2xl  m-4"> مجتمع مدني ومواهب </label>

    </div>
    @php($data=\App\Models\Talent::groupBy('talentType')->selectRaw('count(*) as count, talentType')->get())

    <div class="flex flex-row  justify-center items-center  " >
        <table style="width: 60%;margin-top: 40px;"  >
                @foreach($data as  $row)
                <tr class="h-30 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800" style="height: 40px;">
                          <td  class="text-sm text-center" style="width: 60%">
                                  {{$row->talentType->name}}

                          </td>
                    <td style="width: 20%" class="text-sm ">
                        <div class="flex text-center items-center justify-center">
                            @if($row->talentType->name=='دارنس')
                                <x-filament::avatar
                                    src="{{ public_path('/img/darens.jpg') }}"
                                    size="md"
                                />
                            @endif
                            @if($row->talentType->name=='الافريقي')
                                <x-filament::avatar
                                    src="{{ public_path('/img/afriky.jpg') }}"
                                    size="md"
                                />
                            @endif
                            @if($row->talentType->name=='الهلال_الاحمر')
                                <x-filament::avatar
                                    src="{{ public_path('/img/helal.jpg') }}"
                                    size="md"
                                />
                            @endif
                            @if($row->talentType->name=='الكشافة')
                                <x-filament::avatar
                                    src="{{ public_path('/img/kashaf.jpg') }}"
                                    size="md"
                                />
                            @endif
                                @if($row->talentType->name=='مواهب')
                                    <x-filament::avatar
                                        src="{{ public_path('/img/moaheb.png') }}"
                                        size="md"
                                    />
                                @endif

                        </div>

                    </td>

                          <td class="text-sm text-center" style="width: 20%">
                            @php($talents=\App\Models\Talent::where('talentType',$row->talentType->value)->distinct('id')->pluck('id'))
                            @php($rec= \App\Models\VicTalent::whereIn('talent_id',$talents)->count())
                            {{$rec}}
                          </td>


                </tr>
                @endforeach



</table>
</div>
@pageBreak




@endsection
