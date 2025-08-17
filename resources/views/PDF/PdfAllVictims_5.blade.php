@extends('PDF.PrnMaster4')

@section('mainrep')

    @php $print_count=0; @endphp

        @php

            $familyshow=\App\Models\Familyshow::find($familyshow_id);

            $count=\App\Models\Victim::where('familyshow_id',$familyshow_id)->count();

            $victims=\App\Models\Victim::
            where('familyshow_id',$familyshow_id)
            ->orderBy('masterKey')->get();

        @endphp

        <div >
            <label class=" text-red-500"> آلـ  </label>
            <label  class=" text-2xl font-bold"> {{$familyshow->name}} </label>
        </div>
        <div >
            <label class="text-xl">عدد الشهداء  </label>
            <label  class="text-xl text-red-600"> {{$count}} </label>
        </div>


        @if($familyshow->who)
            <div class="flex">
                <label class="text-gray-500 text-xl">تمت المراجعة بمعرفة : &nbsp; </label>
                <label class="text-gray-500 text-xl">{{$familyshow->who}}</label>
            </div>
        @endif

        <br>

            <div style="position: relative;">
                @foreach($victims as $victim)
                    @if($victim->father_id!=null && !$victim->is_father && !$victim->is_mother
                            && $victim->huband_id==0 && $victim->wife_id==0 )
                        @continue;
                    @endif
                    <div  class="flex ">
                        @if($victim->male=='ذكر')
                            @if($victim->is_great_grandfather)

                                <label   class="text-red-950"> جد الأب : </label>
                            @else
                                @if($victim->is_grandfather)
                                    <label   class="text-red-950">الجد :&nbsp;&nbsp; </label>
                                @else
                                    @if($victim->is_father)
                                    <label  class="text-yellow-700">الأب :&nbsp;&nbsp; </label>
                                    @endif
                                @endif
                            @endif
                            @if($victim->is_father==0 && $victim->wife_id!=null)
                                    <label   class="text-blue-700">الزوج :&nbsp;&nbsp; </label>
                            @endif
                        @endif
                        @if($victim->male=='أنثي')
                                @if($victim->is_great_grandmother)
                                    <p style="color: aqua; ">جدة الأب :&nbsp;&nbsp;</p>
                                @else
                                    @if($victim->is_grandmother)
                                        <p style="color: aqua; ">الجدة : &nbsp;&nbsp;</p>
                                    @else
                                        @if($victim->is_mother)
                                            <p style="color: aqua; ">الأم : &nbsp;&nbsp;</p>
                                        @endif
                                    @endif
                                @endif
                                    @if($victim->is_mother==0 && $victim->husband_id!=null)
                                        <label style="font-size: 14pt;color: #6b21a8" >الزوجة :&nbsp;&nbsp; </label>
                                    @endif

                            @endif
                        {{$victim->FullName}}
                        @if($victim->otherName)
                            <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label>
                        @endif

                        @if($victim->Job)
                            @if($victim->Job->image)
                                <label>&nbsp;</label>
                                <img src="{{ storage_path('app/public/'.$victim->Job->image) }}"  style="width: 26px; height: 26px;" />
                            @endif
                        @endif
                        @if($victim->VicTalent)
                            @foreach($victim->VicTalent as $talent)
                                <label>&nbsp;</label>
                                @if($talent->Talent->image)
                                    <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 26px; height: 26px;" />
                                @endif
                            @endforeach
                        @endif


                        <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <label  > العنوان :  &nbsp</label>
                        <label  >{{$victim->Street->StrName}}</label>
                    </div>


                    @if($victim->wife_id)
                            <div   class="flex">
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <label  class="text-green-500">زوجته : </label>
                            <label  >&nbsp;{{$victim->husband->FullName}}</label>
                            @if($victim->husband->otherName)
                                <label class="text-red-600" >&nbsp;({{$victim->husband->otherName}})</label> ;
                            @endif

                            @if($victim->husband->Job)
                                @if($victim->husband->Job->image)
                                    <label>&nbsp;</label>
                                    <img src="{{ storage_path('app/public/'.$victim->husband->Job->image) }}"  style="width: 26px; height: 26px;" />
                                @endif
                            @endif

                            @if($victim->husband->VicTalent)
                                @foreach($victim->husband->VicTalent as $talent)
                                    <label>&nbsp;</label>
                                    @if($talent->Talent->image)
                                        <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 26px; height: 26px;" />
                                    @endif
                                @endforeach
                            @endif
                            </div>
                    @endif
                    @if($victim->husband_id)
                            <div   class="flex">
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <label  class="text-green-500">زوجها : </label>
                            <label  >&nbsp;{{$victim->wife->FullName}}</label>
                            @if($victim->wife->otherName)
                                <label class="text-red-600" >&nbsp;({{$victim->wife->otherName}})</label> ;
                            @endif

                            @if($victim->wife->Job)
                                @if($victim->wife->Job->image)
                                    <label>&nbsp;</label>
                                    <img src="{{ storage_path('app/public/'.$victim->wife->Job->image) }}"  style="width: 26px; height: 26px;" />
                                @endif
                            @endif

                            @if($victim->wife->VicTalent)
                                @foreach($victim->wife->VicTalent as $talent)
                                    <label>&nbsp;</label>
                                    @if($talent->Talent->image)
                                        <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 26px; height: 26px;" />
                                    @endif
                                @endforeach
                            @endif
                            </div>

                    @endif
                    @if($victim->is_father)
                            <div   class="flex">
                            <div  style="text-align: right;" class="flex">
                                <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <label class=" text-sky-500" >&nbsp;الأبناء :&nbsp; </label>
                            </div>
                            @foreach($victim->father as $son)
                                @if($son->is_father)
                                 <label class="text-yellow-700" >&nbsp;{{$son->Name1}}</label>
                                @else
                                    @if($son->is_mother)
                                        <label style="color: aqua" >&nbsp;{{$son->Name1}}</label>
                                    @else

                                    <label  >&nbsp;{{$son->Name1}}</label>
                                    @endif
                                @endif
                                @if($son->otherName)
                                    <label class="text-red-600" >&nbsp;({{$son->otherName}})</label>
                                @endif
                                @if($son->Job)
                                    @if($son->Job->image)
                                        <label>&nbsp;</label>
                                        <img src="{{ storage_path('app/public/'.$son->Job->image) }}"  style="width: 26px; height: 26px;" />
                                    @endif
                                @endif
                                @if($son->VicTalent)
                                    @foreach($son->VicTalent as $talent)
                                        <label>&nbsp;</label>
                                        @if($talent->Talent->image)
                                            <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 26px; height: 26px;" />
                                        @endif
                                    @endforeach
                                @endif
                                @if(!$loop->last) <label> &nbsp;, </label>@endif
                            @endforeach

                            </div>
                    @endif

                    @if($victim->is_mother)

                        @php
                         echo "<div class=\"flex\">";
                         if ($victim->has_more !=1) {
                              foreach($victim->mother as $son) {
                              $father_name=$son->Name2.' '.$son->Name3.' '.$son->Name4 ;}
                              if($victim->husband_id) echo " <label class=\"text-sky-500\" >&nbsp;&nbsp;&nbsp;الأبناء : </label>";
                              else
                                  echo "<label  class=\"text-sky-500\">&nbsp;&nbsp;&nbsp;ابناءها من </label>
                                  <label>&nbsp; ($father_name) : </label>";
                              $i=0;
                              foreach($victim->mother as $son) {
                              if($i>0)  echo "<label> &nbsp;, </label>";

                                if ($son->is_father) echo " <label  class=\"text-yellow-700 \">&nbsp;$son->Name1</label>";
                                else {
                                    if ($son->is_father) echo " <label  style=\"aqua \">&nbsp;$son->Name1</label>";
                                    else    echo " <label  >&nbsp;$son->Name1</label>";
                                }

                                if($son->otherName)
                                 echo "<label class=\"text-red-600\" >&nbsp;({$son->otherName})</label> ";

                                if($son->Job)
                                  if($son->Job->image)
                                      echo "  <label>&nbsp;</label>
                                      <img src=". storage_path('app/public/'.$son->Job->image) ."  style=\"width: 20px; height: 20px;\" />";
                                  if($son->VicTalent)
                                  foreach($son->VicTalent as $talent) {
                                    echo " <label>&nbsp;</label>";
                                      if($talent->Talent->image)
                                        echo " <img src=". storage_path('app/public/'.$talent->Talent->image) ."  style=\"width: 20px; height: 20px;\" />";

                                 }
                                          $i++;
                             }
                        } else {
                                $rec=\App\Models\Victim::where('mother_id',$victim->id)->orderby('Name2')->get();
                                $i=0;
                                $name2=$rec[0]->Name2;
                                $name3=$rec[0]->Name3;
                                $name4=$rec[0]->Name4;
                                echo "<label class=\"text-sky-500\">(&nbsp;&nbsp;&nbsp;أبناءها من  :  </label> <label>&nbsp;{$name2} {$name3} {$name4}) </label>";
                                foreach($rec as $item){
                                    if ($name2 != $item->Name2){
                                        $name2=$item->Name2;
                                        $name3=$item->Name3;
                                        $name4=$item->Name4;
                                        $i=0;
                                        echo "</div>";
                                        echo "<div class=\"flex\" style=\"text-align: right;\"  >";
                                        echo "<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>";
                                        echo "<label class=\"text-sky-500\">(&nbsp;&nbsp;&nbsp;أبناءها من  :  </label><label>&nbsp;{$name2} {$name3} {$name4}) </label>";
                                    }
                                    echo "<label>&nbsp;</label>";
                                    if ($i===0) { echo "<label> &nbsp;{$item->Name1}</label>";}
                                    else {echo "<label> &nbsp;, {$item->Name1}</label>";}

                                    if($son->otherName)
                                        echo "<label class=\"text-red-600\" >&nbsp;({$son->otherName})</label> ";

                                    if($son->Job)
                                      if($son->Job->image)
                                          echo "  <label>&nbsp;</label>
                                          <img src=". storage_path('app/public/'.$son->Job->image) ."  style=\"width: 20px; height: 20px;\" />";
                                      if($item->VicTalent)
                                      foreach($item->VicTalent as $talent) {
                                        echo " <label>&nbsp;</label>";
                                          if($talent->Talent->image)
                                            echo " <img src=". storage_path('app/public/'.$talent->Talent->image) ."  style=\"width: 20px; height: 20px;\" />";

                                     }
                                                  $i++;

                                                }
                            }
                         echo "</div>" ;
                         @endphp

                        @endif

                        @if($victim->is_great_grandfather || $victim->is_grandfather
                               || $victim->is_father || $victim->is_great_grandmother || $victim->is_grandmother
                               || $victim->is_mother) <br> @endif

                @endforeach


            </div>



@endsection
