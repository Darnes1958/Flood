@extends('PDF.PrnCont')

@section('mainrep')
    <div style="position: relative;">
        <div style="text-align: center;font-size: 12pt;">

            <label>شهداء فيضان وادي درنه</label>
        </div>
        <div style="text-align: center;font-size: 14pt;">
            <label  >{{$tarkeba_name}}</label>
            <label  > التركيبة الإجتماعية :  </label>
        </div>
        <div style="text-align: center;font-size: 14pt;">
            <label  > {{$big_family_name}} </label>
            <label> القبيلة : </label>
        </div>
        <div style="text-align: center;font-size: 14pt;">

            <label  > {{$count}} </label>
            <label>عدد الضحايا فالقبيلة : </label>
        </div>

    </div>
    @foreach($fam as $family)
        <br>
        <div style="position: relative;">
             <span style="font-size: 16pt;">
                  <label  >{{$family->FamName}}</label>
                  <label  style="color: #9f1239" >&nbsp;&nbsp;العائلة : </label>
             </span>
        <div style="position: relative;">
          @foreach($victim_father->where('family_id',$family->id) as $victim)
            <div  style="text-align: right;font-size: 11pt;">
                <label  >{{$victim->Street->StrName}}</label>
                <label  > العنوان </label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                @if($victim->otherName)
                    <label  >{{$victim->FullName}} ({{$victim->otherName}})</label>
                @else
                <label  >{{$victim->FullName}}</label>
                @endif
                <label  style="font-size: 14pt;color: blue">الأب : </label>
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

            @endforeach

        </div>
        <div style="position: relative;">
            @foreach($victim_mother->where('family_id',$family->id) as $victim)
                <div  style="text-align: right;font-size: 11pt;">
                    <label  >{{$victim->Street->StrName}}</label>
                    <label  > العنوان </label>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  >{{$victim->FullName}}</label>
                    <label style="font-size: 14pt;color: #6b21a8" >الأم : </label>
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

            @endforeach

        </div>
        <div style="position: relative;">
            @foreach($victim_husband->where('family_id',$family->id) as $victim)
                <div  style="text-align: right;font-size: 11pt;">
                    <label  >{{$victim->Street->StrName}}</label>
                    <label  > العنوان </label>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  >{{$victim->FullName}}</label>
                    <label  style="font-size: 14pt;color: blue">الزوج : </label>
                </div>
                    <div  style="text-align: right;font-size: 11pt;">
                        <label  >{{$victim->husband->FullName}}</label>
                        <label  >الزوجة : </label>
                    </div>

            @endforeach
        </div>
        <div style="position: relative;">
            @foreach($victim_wife->where('family_id',$family->id) as $victim)
                <div  style="text-align: right;font-size: 11pt;">
                    <label  >{{$victim->Street->StrName}}</label>
                    <label  > العنوان </label>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  >{{$victim->FullName}}</label>
                    <label style="font-size: 14pt;color: #6b21a8" >الزوجة : </label>
                </div>
                <div  style="text-align: right;font-size: 11pt;">
                    <label  >{{$victim->wife->FullName}}</label>
                    <label  >الزوج : </label>
                </div>

            @endforeach
        </div>
        <div style="position: relative;">
            <br>
            @foreach($victim_only->where('family_id',$family->id) as $victim)
                <div  style="text-align: right;font-size: 11pt;">
                    <label  >{{$victim->Street->StrName}}</label>
                    <label  > العنوان </label>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  >{{$victim->FullName}}</label>
                    <label>&nbsp;&nbsp;</label>
                </div>
            @endforeach
        </div>

    @endforeach
@endsection







