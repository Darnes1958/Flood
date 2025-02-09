@extends('PDF.PrnMaster3')

@section('mainrep')



        <div class="flex flex-row  justify-center items-center " >
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
                <label class="text-2xl  m-2 ">أباء  : </label>
                <label  class="text-2xl text-red-600"> {{$father}} </label>
            </div>
            <div class="basis-1/2 ">
                <label class="text-2xl m-2">أمهات  : </label>
                <label  class="text-2xl text-red-600"> {{$mother}} </label>
            </div>
        </div>

            <div class="mt-10 mr-20">
                <label class="text-2xl  m-2 ">زوجات أجنبيات  : </label>
                <label  class="text-2xl text-red-600"> {{$forignWives}} </label>
            </div>
            <div class="mt-10 mr-20">
                <label class="text-2xl m-2">متزوجات من أجانب  : </label>
                <label  class="text-2xl text-red-600"> {{$forignHusband}} </label>
            </div>


            <div class="mt-10 mr-20">
                <label class="text-2xl  m-2 ">أثناء العمل  : </label>
                <label  class="text-2xl text-red-600"> {{$in_work}} </label>
            </div>
        <div class="mt-10 mr-20">
                <label class="text-2xl m-2">فالإنقاذ  : </label>
                <label  class="text-2xl text-red-600"> {{$at_save}} </label>
            </div>
        <div class="mt-10 mr-20">
                <label class="text-2xl m-2">ضيوف وزوار  : </label>
                <label  class="text-2xl text-red-600"> {{$guest}} </label>
            </div>


        <div class="mt-10 mr-20">
                <label class="text-2xl  m-2 ">شرق الوادي  : </label>
                <label  class="text-2xl text-red-600"> {{$east}} </label>
            </div>
        <div class="mt-10 mr-20">
                <label class="text-2xl m-2">غرب الوادي  : </label>
                <label  class="text-2xl text-red-600"> {{$west}} </label>
        </div>
        <div class="mt-10 mr-20">
            <label class="text-2xl  m-2 ">وادي درنه  : </label>
            <label  class="text-2xl text-red-600"> {{$derna}} </label>
        </div>
        <div class="mt-10 mr-20">
            <label class="text-2xl m-2">ودي الناقة  : </label>
            <label  class="text-2xl text-red-600"> {{$naga}} </label>
        </div>




@endsection
