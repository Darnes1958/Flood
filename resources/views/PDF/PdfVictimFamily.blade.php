@extends('PDF.PrnCont')

@section('mainrep')
    <div style="position: relative;">
        <div style="text-align: center;font-size: 12pt;">

            <label>شهداء فيضان وادي درنه</label>
        </div>
        <div style="text-align: center;font-size: 14pt;">
            <label  >{{$tribe_name}}</label>
            <label  > القبيلة أو التركيبة الإجتماعية :  </label>
        </div>
        <div style="text-align: center;font-size: 14pt;">
            <label  > {{$family_name}} </label>
            <label> العائلة أو القبيلة : </label>
        </div>
        <div style="text-align: center;font-size: 14pt;">

            <label  > {{$count}} </label>
            <label>عدد الضحايا فالقبيلة : </label>
        </div>
        @if($bait_name)
            <div style="text-align: center;font-size: 14pt;">
                <label  > {{$bait_name}} </label>
                <label> البيت : </label>
            </div>
            <div style="text-align: center;font-size: 14pt;">

                <label  > {{$bait_count}} </label>
                <label>عدد الضحايا فالبيت : </label>
            </div>
        @endif

        <br>
      @foreach($victim_father as $victim)
        <div  style="text-align: right;font-size: 11pt;">
            <label  >{{$victim->Street->StrName}}</label>
            <label  > العنوان </label>
            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            @if($victim->otherName)
                <label  >{{$victim->FullName}} ({{$victim->otherName}})</label>
            @else
            <label  >{{$victim->FullName}}</label>
            @endif
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

                @php
                    if ($victim->has_more==1) ;
                         if ($victim->has_more !=1) {
                           foreach($victim->mother as $son) {
                               $father_name=$son->Name2.' '.$son->Name3.' '.$son->Name4 ;
                               echo " <label  >$son->Name1</label>";
                               if(!$loop->last)  echo "<label> , </label>";
                           }

                           if($victim->husband_id) echo "<label  >الأبناء : </label>";
                           else
                               echo "<label  >ابناءها من ($father_name) : </label>";

                         } else {
                                 $rec=\App\Models\Victim::where('mother_id',$victim->id)->orderby('Name2')->get();
                                 $i=0;
                                 $name2=$rec[0]->Name2;
                                 $name3=$rec[0]->Name3;
                                 $name4=$rec[0]->Name4;
                                 foreach($rec as $item){
                                     if ($name2 != $item->Name2){
                                         echo "<label>(أبناءها من  :  {$name2} {$name3} {$name4}) </label>";
                                         echo "<br/>";
                                         $name2=$item->Name2;
                                         $name3=$item->Name3;
                                         $name4=$item->Name4;
                                         $i=0;
                                     }
                                     if ($i===0) { echo "<label> {$item->Name1}</label>";}
                                     else {echo "<label> {$item->Name1},</label>";}
                                     $i++;
                                 }
                                 echo "<label>(أبناءها من  :  {$name2} {$name3} {$name4}) </label>";
                             }


                @endphp

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







