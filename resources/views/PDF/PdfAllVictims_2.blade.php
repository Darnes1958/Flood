@extends('PDF.PrnMaster3')

@section('mainrep')

    @php $print_count=0; @endphp
    @foreach($familyshowAll as $show)
            @php
            $familyshow_id=$show->id;
            $familyshow=\App\Models\Familyshow::find($familyshow_id);

            $families=\App\Models\Family::where('familyshow_id',$familyshow_id)->pluck('id')->all();

            $fam=\App\Models\Family::whereIn('id',$families)->get();

            $count=\App\Models\Victim::whereIn('family_id',$families)->count();

            $victim_father=\App\Models\Victim::with('father')
            ->whereIn('family_id',$families)
            ->where('is_father','1')->get();

            $fathers=\App\Models\Victim::whereIn('family_id',$families)->where('is_father',1)->select('id');
            $mothers=\App\Models\Victim::whereIn('family_id',$families)->where('is_mother',1)->select('id');

            $victim_mother=\App\Models\Victim::with('mother')
            ->whereIn('family_id',$families)
            ->where('is_mother','1')
            ->where(function ( $query) use($fathers) {
            $query->where('husband_id', null)
            ->orwhere('husband_id', 0)
            ->orwhereNotIn('husband_id',$fathers);
            })

            ->get();

            $victim_husband=\App\Models\Victim::
            whereIn('family_id',$families)
            ->where('male','ذكر')
            ->where('is_father','0')
            ->where('wife_id','!=',null)
            ->get();

            $victim_wife=\App\Models\Victim::
            whereIn('family_id',$families)

            ->where('male','أنثي')
            ->where('is_mother','0')
            ->where('husband_id','!=',null)
            ->get();
            $victim_only=\App\Models\Victim::
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
        @endphp

            <div >
                <label class="text-xl text-red-500"> العائلة  : </label>
                <label  class="text-xl  font-bold"> {{$familyshow->name}} </label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label class="text-sm">عدد الضحايا  : </label>
                <label  class="text-sm text-red-600"> {{$count}} </label>
            </div>

            @if($fam->count()>1)
                <div class="flex">
                    <label class="text-sm">(</label>

                    @foreach($fam as $family)
                        @if(\App\Models\Victim::where('family_id',$family->id)->count()>0 )
                            <label class="text-sm" >{{$family->FamName}}</label>
                            <label  class="text-sm" >&nbsp;{{$family->victim->count()}}</label>
                            @if(!$loop->last) <label> &nbsp;,&nbsp; </label>@endif
                        @endif
                    @endforeach
                    <label class="text-sm">)</label>
                </div>
            @endif

            @if($familyshow->who)
                <div class="flex">
                    <label class="text-gray-500 text-sm">تمت المراجعة بمعرفة : &nbsp; </label>
                    <label class="text-gray-500 text-sm">{{$familyshow->who}}</label>
                </div>
            @endif


    <br>
    @foreach($fam as $family)
        @if($fam->count()>1)
            @if(\App\Models\Victim::where('family_id',$family->id)->count()>0 )
                <div style="position: relative;">
                    @if(!$loop->first) <br> @endif
                    <span style="font-size: 14pt;">
                             <label  style="color: #9f1239" >&nbsp;&nbsp;اللقب :</label>
                             <label  >{{$family->FamName}}</label>
                         </span>
                </div>
            @endif
        @endif
        <div style="position: relative;">
            @foreach($victim_father->where('family_id',$family->id) as $victim)
                <div  style="text-align: right;font-size: 11pt;" class="flex">
                    @if($victim->is_grandfather)
                        <label  style="font-size: 14pt;" class="text-red-950">الجد : </label>
                    @else
                        <label  style="font-size: 14pt;" class="text-yellow-700">الأب : </label>
                    @endif

                    @if($victim->otherName)
                        <label  >&nbsp;{{$victim->FullName}} ({{$victim->otherName}})</label>
                    @else
                        <label  >&nbsp;{{$victim->FullName}}</label>
                    @endif
                    @if($victim->Job)
                        @if($victim->Job->image)
                            <label>&nbsp;</label>
                            <img src="{{ storage_path('app/public/'.$victim->Job->image) }}"  style="width: 20px; height: 20px;" />
                        @endif
                    @endif
                    @if($victim->VicTalent)
                        @foreach($victim->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->Talent->image)
                                <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 20px; height: 20px;" />
                            @endif
                        @endforeach
                    @endif


                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  > العنوان :  &nbsp</label>
                    <label  >{{$victim->Street->StrName}}</label>
                </div>
                @if($victim->wife_id)
                    <div  style="text-align: right;font-size: 11pt;" class="flex">
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <label  class="text-green-500">زوجته : </label>
                        <label  >&nbsp;{{$victim->husband->FullName}}</label>
                        @if($victim->husband->otherName)
                            <label class="text-red-600" >&nbsp;({{$victim->husband->otherName}})</label> ;
                        @endif

                        @if($victim->husband->Job)
                            @if($victim->husband->Job->image)
                                <label>&nbsp;</label>
                                <img src="{{ storage_path('app/public/'.$victim->husband->Job->image) }}"  style="width: 20px; height: 20px;" />
                            @endif
                        @endif



                        @if($victim->husband->VicTalent)
                            @foreach($victim->husband->VicTalent as $talent)
                                <label>&nbsp;</label>
                                @if($talent->Talent->image)
                                    <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 20px; height: 20px;" />
                                @endif
                            @endforeach
                        @endif


                    </div>
                @endif

                <div  style="text-align: right;font-size: 11pt;" class="flex">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label class=" text-sky-500" >الأبناء : </label>
                    @foreach($victim->father as $son)
                        <label  >&nbsp;{{$son->Name1}}</label>
                        @if($son->otherName)
                            <label class="text-red-600" >&nbsp;({{$son->otherName}})</label>
                        @endif
                        @if($son->Job)
                            @if($son->Job->image)
                                <label>&nbsp;</label>
                                <img src="{{ storage_path('app/public/'.$son->Job->image) }}"  style="width: 20px; height: 20px;" />
                            @endif
                        @endif



                        @if($son->VicTalent)
                            @foreach($son->VicTalent as $talent)
                                <label>&nbsp;</label>
                                @if($talent->Talent->image)
                                    <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 20px; height: 20px;" />
                                @endif
                            @endforeach
                        @endif


                        @if(!$loop->last) <label> , </label>@endif
                    @endforeach

                </div>

            @endforeach
        </div>
        <div style="position: relative;" >
            @php
                foreach($victim_mother->where('family_id',$family->id) as $victim) {
                    echo "<div  style=\"text-align: right;font-size: 11pt;\" class=\"flex\">";
                    if ($victim->is_grandmother)
                        echo "<label style=\"font-size: 14pt;\" class=\"text-red-950\" >الجدة : </label>";
                    else
                        echo "<label style=\"font-size: 14pt;\" class=\"text-green-500\" >الأم : </label>";
                    echo "<label  >&nbsp;$victim->FullName</label>";
                    if($victim->otherName)
                        echo "<label class=\"text-red-600\" >&nbsp;({$victim->otherName})</label> ";
                     if($victim->Job)
                        if($victim->Job->image)
                            echo "  <label>&nbsp;</label>
                            <img src=". storage_path('app/public/'.$victim->Job->image) ."  style=\"width: 20px; height: 20px;\" />";






                    echo  "<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <label  > العنوان :  &nbsp</label>
                        <label  >{$victim->Street->StrName}</label> ";

                    echo "</div>";
                    if($victim->husband_id){
                      echo  "<div  style=\"text-align: right;font-size: 11pt;\" class=\"flex\">
                      <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <label class=\"text-yellow-700\" >زوجها : </label>
                            <label  >&nbsp;{$victim->wife->FullName}</label>";
                    if($victim->wife->otherName)
                        echo "<label class=\"text-red-600\" >&nbsp;({$victim->wife->otherName})</label> ";
                    if($victim->wife->Job)
                        if($victim->wife->Job->image)
                            echo "  <label>&nbsp;</label>
                            <img src=". storage_path('app/public/'.$victim->wife->Job->image) ."  style=\"width: 20px; height: 20px;\" />";


                    if($victim->wife->VicTalent)
                        foreach($victim->wife->VicTalent as $talent) {
                          echo " <label>&nbsp;</label>";
                            if($talent->Talent->image)
                              echo " <img src=". storage_path('app/public/'.$talent->Talent->image) ."  style=\"width: 20px; height: 20px;\" />";

                       }

                    echo      "</div>";}

                      echo "<div  style=\"text-align: right;font-size: 11pt;\" class=\"flex\" >
                      <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>";


                          if ($victim->has_more !=1) {
                              foreach($victim->mother as $son) {
                                $father_name=$son->Name2.' '.$son->Name3.' '.$son->Name4 ;}
                            if($victim->husband_id) echo "<label class=\"text-sky-500\" >الأبناء : </label>";
                            else
                                echo "<label  class=\"text-sky-500\">ابناءها من </label>
                                <label>&nbsp; ($father_name) : </label>";
                              $i=0;
                              foreach($victim->mother as $son) {
                                if($i>0)  echo "<label> , </label>";
                                  echo " <label  >&nbsp;$son->Name1</label>";
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
                                  echo "<label class=\"text-sky-500\">(أبناءها من  :  </label> <label>&nbsp;{$name2} {$name3} {$name4}) </label>";
                                  foreach($rec as $item){
                                      if ($name2 != $item->Name2){
                                          $name2=$item->Name2;
                                          $name3=$item->Name3;
                                          $name4=$item->Name4;
                                          $i=0;
                                          echo "</div>";
                                          echo "<div  style=\"text-align: right;font-size: 11pt;\" class=\"flex\" >";
                                          echo "<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>";
                                          echo "<label class=\"text-sky-500\">(أبناءها من  :  </label><label>&nbsp;{$name2} {$name3} {$name4}) </label>";


                                      }
                                      echo "<label>&nbsp;</label>";
                                      if ($i===0) { echo "<label> &nbsp;{$item->Name1}</label>";}
                                      else {echo "<label> , {$item->Name1}</label>";}

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
                }
            @endphp

        </div>
        <div style="position: relative;">
            @foreach($victim_husband->where('family_id',$family->id) as $victim)
                <div  style="text-align: right;font-size: 11pt;" class="flex">
                    <label  style="font-size: 14pt;" class="text-blue-700">الزوج : </label>
                    <label  >&nbsp;{{$victim->FullName}}</label>
                    @if($victim->otherName)
                        <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label>
                    @endif
                    @if($victim->VicTalent)
                        @foreach($victim->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->Talent->image)
                                <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 20px; height: 20px;" />
                            @endif
                        @endforeach
                    @endif

                    @if($victim->Job)
                        @if($victim->Job->image)
                            <label>&nbsp;</label>
                            <img src="{{ storage_path('app/public/'.$victim->Job->image) }}"  style="width: 20px; height: 20px;" />
                        @endif
                    @endif

                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  > العنوان :  &nbsp</label>
                    <label  >{{$victim->Street->StrName}}</label>
                </div>
                <div  style="text-align: right;font-size: 11pt;" class="flex">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  class="text-fuchsia-500">الزوجة : </label>
                    <label  >&nbsp;{{$victim->husband->FullName}}</label>
                    @if($victim->husband->otherName)
                        <label class="text-red-600" >&nbsp;({{$victim->husband->otherName}})</label>
                    @endif
                    @if($victim->husband->Job)
                        @if($victim->husband->Job->image)
                            <label>&nbsp;</label>
                            <img src="{{ storage_path('app/public/'.$victim->husband->Job->image) }}"  style="width: 20px; height: 20px;" />
                        @endif
                    @endif
                    @if($victim->husband->VicTalent)
                        @foreach($victim->husband->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->Talent->image)
                                <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 20px; height: 20px;" />
                            @endif
                        @endforeach
                    @endif

                </div>

            @endforeach
        </div>
        <div style="position: relative;">
            @foreach($victim_wife->where('family_id',$family->id) as $victim)
                <div  style="text-align: right;font-size: 11pt;" class="flex">
                    <label style="font-size: 14pt;color: #6b21a8" >الزوجة : </label>
                    <label  >&nbsp;{{$victim->FullName}}</label>
                    @if($victim->otherName)
                        <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label>
                    @endif
                    @if($victim->Job)
                        @if($victim->Job->image)
                            <label>&nbsp;</label>
                            <img src="{{ storage_path('app/public/'.$victim->Job->image) }}"  style="width: 20px; height: 20px;" />
                        @endif
                    @endif
                    @if($victim->VicTalent)
                        @foreach($victim->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->Talent->image)
                                <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 20px; height: 20px;" />
                            @endif
                        @endforeach
                    @endif

                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  > العنوان :  &nbsp</label>
                    <label  >{{$victim->Street->StrName}}</label>

                </div>
                <div  style="text-align: right;font-size: 11pt;" class="flex">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  >الزوج : </label>
                    <label  >{{$victim->wife->FullName}}</label>
                    @if($victim->wife->otherName)
                        <label class="text-red-600" >&nbsp;({{$victim->wife->otherName}})</label>
                    @endif
                    @if($victim->wife->Job)
                        @if($victim->wife->Job->image)
                            <label>&nbsp;</label>
                            <img src="{{ storage_path('app/public/'.$victim->wife->Job->image) }}"  style="width: 20px; height: 20px;" />
                        @endif
                    @endif
                    @if($victim->wife->VicTalent)
                        @foreach($victim->wife->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->Talent->image)
                                <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 20px; height: 20px;" />
                            @endif
                        @endforeach
                    @endif

                </div>

            @endforeach
        </div>
        <div style="position: relative;">
            @if($victim_only->where('family_id',$family->id)->count()>0 && ($victim_father->where('family_id',$family->id)->count()>0
                || $victim_mother->where('family_id',$family->id)->count()>0 || $victim_husband->where('family_id',$family->id)->count()>0
                || $victim_husband->where('family_id',$family->id)->count()>0))
                <br>
            @endif

            @foreach($victim_only->where('family_id',$family->id) as $victim)
                <div  style="text-align: right;font-size: 11pt;" class="flex">

                    <label  >&nbsp;{{$victim->FullName}}</label>
                    @if($victim->otherName)
                        <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label>
                    @endif
                    @if($victim->Job)
                        @if($victim->Job->image)
                            <label>&nbsp;</label>
                            <img src="{{ storage_path('app/public/'.$victim->Job->image) }}"  style="width: 20px; height: 20px;" />
                        @endif
                    @endif
                    @if($victim->VicTalent)
                        @foreach($victim->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->Talent->image)
                                <img src="{{ storage_path('app/public/'.$talent->Talent->image) }}"  style="width: 20px; height: 20px;" />
                            @endif
                        @endforeach
                    @endif

                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label  > العنوان :  &nbsp</label>
                    <label  >{{$victim->Street->StrName}}</label>
                    <label>&nbsp;&nbsp;</label>
                </div>
            @endforeach
        </div>



    @endforeach



    @if($count>7)
        @pageBreak
    @endif
    @if($count<8 && $count>5)
        @php $print_count++; @endphp
        @if($print_count>1)
            @php $print_count=0; @endphp
            @pageBreak
        @else
            <br>
        @endif
    @endif

            @if($count<6 && $count>2)
                @php $print_count++; @endphp
                @if($print_count>2)
                    @php $print_count=0; @endphp
                    @pageBreak
                @else
                    <br>
                @endif
            @endif

            @if($count<3 )
                @php $print_count++; @endphp
                @if($print_count>3)
                    @php $print_count=0; @endphp
                    @pageBreak
                @else
                    <br>
                @endif
            @endif

    @endforeach



@endsection
