@extends('PDF.PrnCont')

@section('mainrep')
    <div style="position: relative;">
        <div style="text-align: center;font-size: 12pt;">

            <label>شهداء فيضان وادي درنه</label>
        </div>
        <div style="text-align: center;font-size: 14pt;">
            <label  > {{$family_name}} </label>
            <label> العائلة أو القبيلة : </label>
        </div>
        <div style="text-align: center;font-size: 14pt;">
            <label  >{{$tribe_name}}</label>
            <label  > القبيلة أو التركيبة الإجتماعية :  </label>
        </div>
        <div style="text-align: center;font-size: 14pt;">

            <label  > {{$count}} </label>
            <label>العدد : </label>
        </div>

        <br>
      @foreach($victim_father as $victim)
        <div  style="text-align: right;font-size: 11pt;">
            <label  >{{$victim->Street->StrName}}</label>
            <label  > العنوان </label>
            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label  >{{$victim->FullName}}</label>
            <label  >الأب : </label>
        </div>
        @if($victim->wife_id)
            <div  style="text-align: right;font-size: 11pt;">
                    <label  >{{$victim->husband->FullName}}</label>
                <label  >زوجته : </label>
            </div>
        @endif

            <div  style="text-align: right;font-size: 11pt;">
            @foreach($victim->father as $son)
                <label  >{{$son->Name1}}</label>
                @if(!$loop->last) <label> , </label>@endif
            @endforeach
            <label  >الأبناء : </label>
        </div>
          <br>
        @endforeach
    </div>
    <div style="position: relative;">
        @foreach($victim_mother as $victim)
            <div  style="text-align: right;font-size: 11pt;">
                <label  >{{$victim->Street->StrName}}</label>
                <label  > العنوان </label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label  >{{$victim->FullName}}</label>
                <label  >الأم : </label>
            </div>
            @if($victim->husband_id)
                <div  style="text-align: right;font-size: 11pt;">
                    <label  >{{$victim->wife->FullName}}</label>
                    <label  >زوجها : </label>
                </div>

            @endif

            <div  style="text-align: right;font-size: 11pt;">
                @foreach($victim->mother as $son)
                    @php $father_name=$son->Name2.' '.$son->Name3.' '.$son->Name4 @endphp
                    <label  >{{$son->Name1}}</label>
                    @if(!$loop->last) <label> , </label>@endif
                @endforeach
                @if($victim->husband_id) <label  >الأبناء : </label>
                    @else
                        <label  >ابناءها من ({{$father_name}}) : </label>
                    @endif
            </div>
            <br>
        @endforeach
    </div>
    <div style="position: relative;">
        @foreach($victim_husband as $victim)
            <div  style="text-align: right;font-size: 11pt;">
                <label  >{{$victim->Street->StrName}}</label>
                <label  > العنوان </label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label  >{{$victim->FullName}}</label>
                <label  >الزوج : </label>
            </div>
                <div  style="text-align: right;font-size: 11pt;">
                    <label  >{{$victim->husband->FullName}}</label>
                    <label  >الزوجة : </label>
                </div>
            <br>
        @endforeach
    </div>
    <div style="position: relative;">
        @foreach($victim_wife as $victim)
            <div  style="text-align: right;font-size: 11pt;">
                <label  >{{$victim->Street->StrName}}</label>
                <label  > العنوان </label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label  >{{$victim->FullName}}</label>
                <label  >الزوجة : </label>
            </div>
            <div  style="text-align: right;font-size: 11pt;">
                <label  >{{$victim->wife->FullName}}</label>
                <label  >الزوج : </label>
            </div>
            <br>
        @endforeach
    </div>
    <div style="position: relative;">
        @foreach($victim_only as $victim)
            <div  style="text-align: right;font-size: 11pt;">
                <label  >{{$victim->Street->StrName}}</label>
                <label  > العنوان </label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label  >{{$victim->FullName}}</label>
                <label>&nbsp;&nbsp;</label>
            </div>
        @endforeach
    </div>

@endsection







