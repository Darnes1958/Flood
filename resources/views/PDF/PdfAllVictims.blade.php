@extends('PDF.PrnMaster3')

@section('mainrep')

    <div style="position: relative;">

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


    </div>
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
                    <label  style="font-size: 14pt;" class="text-yellow-700">الأب : </label>
                    @if($victim->otherName)
                        <label  >&nbsp;{{$victim->FullName}} ({{$victim->otherName}})</label>
                    @else
                        <label  >&nbsp;{{$victim->FullName}}</label>
                    @endif
                    @if($victim->Job)
                        <label>&nbsp;</label>
                        @if($victim->Job->jobType->name=='القوات_المسلحة')
                            <x-filament::avatar
                                src="{{ base_path('public/img/milatary.jpg') }}"
                                size="sm"
                            />
                        @endif
                        @if($victim->Job->jobType->name=='الداخلية')
                            <x-filament::avatar
                                src="{{ base_path('public/img/aladel.jpeg') }}"
                                size="sm"
                            />

                        @endif
                    @endif
                    @if($victim->VicTalent)
                        @foreach($victim->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->talent->talentType->name=='دارنس')
                                <img
                                    src="{{ base_path('public/img/darens.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الافريقي')
                                <img
                                    src="{{ base_path('public/img/afriky.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الهلال_الاحمر')
                                <img
                                    src="{{ base_path('public/img/helal.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الكشافة')
                                <img
                                    src="{{ base_path('public/img/kashaf.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
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
                        @if($victim->husband->Job)
                            <label>&nbsp;</label>
                            @if($victim->husband->Job->jobType->name=='القوات_المسلحة')
                                <x-filament::avatar
                                    src="{{ base_path('public/img/milatary.jpg') }}"
                                    size="sm"
                                />
                            @endif
                            @if($victim->husband->Job->jobType->name=='الداخلية')
                                <x-filament::avatar
                                    src="{{ base_path('public/img/aladel.jpeg') }}"
                                    size="sm"
                                />
                            @endif
                        @endif


                    @if($victim->husband->otherName)
                         <label class="text-red-600" >&nbsp;({{$victim->husband->otherName}})</label> ;
                        @endif
                        @if($victim->husband->VicTalent)
                            @foreach($victim->husband->VicTalent as $talent)
                                <label>&nbsp;</label>
                                @if($talent->talent->talentType->name=='دارنس')
                                    <img
                                        src="{{ base_path('public/img/darens.jpg') }}"
                                        style="width: 20px; height: 20px;"
                                    />
                                @endif
                                @if($talent->talent->talentType->name=='الافريقي')
                                    <img
                                        src="{{ base_path('public/img/afriky.jpg') }}"
                                        style="width: 20px; height: 20px;"
                                    />
                                @endif
                                @if($talent->talent->talentType->name=='الهلال_الاحمر')
                                    <img
                                        src="{{ base_path('public/img/helal.jpg') }}"
                                        style="width: 20px; height: 20px;"
                                    />
                                @endif
                                @if($talent->talent->talentType->name=='الكشافة')
                                    <img
                                        src="{{ base_path('public/img/kashaf.jpg') }}"
                                        style="width: 20px; height: 20px;"
                                    />
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
                            <label class="text-red-600" >&nbsp;({{$son->otherName}})</label> ;
                        @endif
                        @if($son->Job)
                            <label>&nbsp;</label>
                            @if($son->Job->jobType->name=='القوات_المسلحة')
                                <x-filament::avatar
                                    src="{{ base_path('public/img/milatary.jpg') }}"
                                    size="sm"
                                />
                            @endif
                            @if($son->Job->jobType->name=='الداخلية')
                                <x-filament::avatar

                                    src="{{ base_path('public/img/aladel.jpeg') }}"
                                    size="sm"
                                />
                            @endif
                        @endif

                        @if($son->VicTalent)

                            @foreach($son->VicTalent as $talent)

                                @if($talent->talent->talentType->name=='دارنس')
                                    <img
                                        src="{{ base_path('public/img/darens.jpg') }}"
                                        style="width: 20px; height: 20px;"
                                    />
                                @endif
                                @if($talent->talent->talentType->name=='الافريقي')
                                    <img
                                        src="{{ base_path('public/img/afriky.jpg') }}"
                                        style="width: 20px; height: 20px;"
                                    />
                                @endif
                                @if($talent->talent->talentType->name=='الهلال_الاحمر')
                                    <img
                                        src="{{ base_path('public/img/helal.jpg') }}"
                                        style="width: 20px; height: 20px;"
                                    />
                                @endif
                                @if($talent->talent->talentType->name=='الكشافة')
                                    <img
                                        src="{{ base_path('public/img/kashaf.jpg') }}"
                                        style="width: 20px; height: 20px;"
                                    />
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
                    echo "<div  style=\"text-align: right;font-size: 11pt;\" class=\"flex\">
                        <label style=\"font-size: 14pt;\" class=\"text-green-500\" >الأم : </label>
                        <label  >&nbsp;$victim->FullName</label>";
                    if($victim->otherName)
                        echo "<label class=\"text-red-600\" >&nbsp;({$victim->otherName})</label> ";
                    if($victim->Job){
                        echo "<label>&nbsp;</label>";
                        if($victim->Job->jobType->name=='القوات_المسلحة')
                        echo " <img
                                            src= ". base_path('public/img/milatary.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";

                        if($victim->Job->jobType->name=='الداخلية')
                        echo "   <x-filament::avatar
                                src=". base_path('public/img/aladel.jpeg') .
                                "size=\"sm\"
                            />";
                        }
                    if ($victim->VicTalent)
                        foreach($victim->VicTalent as $talent) {
                           echo "<label>&nbsp;</label>";
                                       if($talent->talent->talentType->name=='دارنس')
                                          echo " <img
                                            src= ". base_path('public/img/darens.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
                                        if($talent->talent->talentType->name=='الافريقي')
                                            echo "<img
                                            src= ". base_path('public/img/afriky.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
                                        if($talent->talent->talentType->name=='الهلال_الاحمر')
                                            echo " <img
                                            src= ". base_path('public/img/helal.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
                                        if($talent->talent->talentType->name=='الكشافة')
                                        echo "<img
                                            src= ". base_path('public/img/kashaf.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
                            }

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

                    if($victim->wife->Job){
                        echo "<label>&nbsp;</label>";
                        if($victim->wife->Job->jobType->name=='القوات_المسلحة')
                        echo " <img
                                            src= ". base_path('public/img/milatary.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";

                        if($victim->wife->Job->jobType->name=='الداخلية')
                        echo "   <x-filament::avatar
                                src=". base_path('public/img/aladel.jpeg') .
                                "size=\"sm\"
                            />";
                        }

                    if ($victim->wife->VicTalent)
                        foreach($victim->wife->VicTalent as $talent) {
                           echo "<label>&nbsp;</label>";
                            if ($talent->talent->talentType->name=='دارنس')
                              echo " <img
                                    src=". base_path('public/img/darens.jpg') ."
                                    style=\"width: 20px; height: 20px;\"
                                />";

                            if ($talent->talent->talentType->name=='الافريقي')
                                echo "<img
                                    src=". base_path('public/img/afriky.jpg')."
                                    style=\"width: 20px; height: 20px;\"
                                />";

                            if ($talent->talent->talentType->name=='الهلال_الاحمر')
                                echo "<img
                                    src=". base_path('public/img/helal.jpg')."
                                    style=\"width: 20px; height: 20px;\"
                                />";


                            if ($talent->talent->talentType->name=='الكشافة')
                             echo "  <img
                                    src=". base_path('public/img/kashaf.jpg')."
                                    style=\"width: 20px; height: 20px;\"
                                />";
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
                    if($son->Job){
                        echo "<label>&nbsp;</label>";
                        if($son->Job->jobType->name=='القوات_المسلحة')
                        echo " <img
                                            src= ". base_path('public/img/milatary.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";

                        if($son->Job->jobType->name=='الداخلية')
                        echo "   <x-filament::avatar
                                src=". base_path('public/img/aladel.jpeg') .
                                "size=\"sm\"
                            />";
                        }

                                  if($son->VicTalent)
                                   foreach($son->VicTalent as $talent) {
                                        echo "<label>&nbsp;</label>";
                                       if($talent->talent->talentType->name=='دارنس')
                                          echo " <img
                                            src= ". base_path('public/img/darens.jpg').
                                            " style=\"width: 20px; height: 20px;\"/>";

                                        if($talent->talent->talentType->name=='الافريقي')
                                            echo "<img
                                            src= ". base_path('public/img/afriky.jpg').
                                            " style=\"width: 20px; height: 20px;\"/>";
                                        if($talent->talent->talentType->name=='الهلال_الاحمر')
                                            echo " <img
                                            src= ". base_path('public/img/helal.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";

                                        if($talent->talent->talentType->name=='الكشافة')
                                        echo "<img
                                            src= ". base_path('public/img/kashaf.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
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
                    if($son->Job){
                        echo "<label>&nbsp;</label>";
                        if($son->Job->jobType->name=='القوات_المسلحة')
                        echo " <img
                                            src= ". base_path('public/img/milatary.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";

                        if($son->Job->jobType->name=='الداخلية')
                        echo "   <x-filament::avatar
                                src=". base_path('public/img/aladel.jpeg').
                                "size=\"sm\"
                            />";
                        }

                                      if($item->VicTalent)
                                         foreach($item->VicTalent as $talent) {
                                       echo "<label>&nbsp;</label>";
                                       if($talent->talent->talentType->name=='دارنس')
                                          echo " <img
                                            src= ". base_path('public/img/darens.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
                                        if($talent->talent->talentType->name=='الافريقي')
                                            echo "<img
                                            src= ". base_path('public/img/afriky.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
                                        if($talent->talent->talentType->name=='الهلال_الاحمر')
                                            echo " <img
                                            src= ". base_path('public/img/helal.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
                                        if($talent->talent->talentType->name=='الكشافة')
                                        echo "<img
                                            src= ". base_path('public/img/kashaf.jpg').
                                            " style=\"width: 20px; height: 20px;\"
                                            />";
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
                    @if($victim->VicTalent)
                        @foreach($victim->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->talent->talentType->name=='دارنس')
                                <img
                                    src="{{ base_path('public/img/darens.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الافريقي')
                                <img
                                    src="{{ base_path('public/img/afriky.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الهلال_الاحمر')
                                <img
                                    src="{{ base_path('public/img/helal.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الكشافة')
                                <img
                                    src="{{ base_path('public/img/kashaf.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                        @endforeach
                    @endif
                    @if($victim->otherName)
                        <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label> ;
                    @endif
                    @if($victim->Job)
                        <label>&nbsp;</label>
                        @if($victim->Job->jobType->name=='القوات_المسلحة')
                            <x-filament::avatar
                                src="{{ base_path('public/img/milatary.jpg') }}"
                                size="sm"
                            />
                        @endif
                        @if($victim->Job->jobType->name=='الداخلية')
                            <x-filament::avatar
                                src="{{ base_path('public/img/aladel.jpeg') }}"
                                size="sm"
                            />
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
                        <label class="text-red-600" >&nbsp;({{$victim->husband->otherName}})</label> ;
                    @endif
                    @if($victim->husband->Job)
                        <label>&nbsp;</label>
                        @if($victim->husband->Job->jobType->name=='القوات_المسلحة')
                            <x-filament::avatar
                                src="{{ base_path('public/img/milatary.jpg') }}"
                                size="sm"
                            />
                        @endif
                        @if($victim->husband->Job->jobType->name=='الداخلية')
                            <x-filament::avatar
                                src="{{ base_path('public/img/aladel.jpeg') }}"
                                size="sm"
                            />
                        @endif
                    @endif

                @if($victim->husband->VicTalent)
                        @foreach($victim->husband->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->talent->talentType->name=='دارنس')
                                <img
                                    src="{{ base_path('public/img/darens.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الافريقي')
                                <img
                                    src="{{ base_path('public/img/afriky.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الهلال_الاحمر')
                                <img
                                    src="{{ base_path('public/img/helal.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الكشافة')
                                <img
                                    src="{{ base_path('public/img/kashaf.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
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
                        <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label> ;
                    @endif
                    @if($victim->Job)
                        <label>&nbsp;</label>
                        @if($victim->Job->jobType->name=='القوات_المسلحة')
                            <x-filament::avatar
                                src="{{ base_path('public/img/milatary.jpg') }}"
                                size="sm"
                            />
                        @endif
                        @if($victim->Job->jobType->name=='الداخلية')
                            <x-filament::avatar
                                src="{{ base_path('public/img/aladel.jpeg') }}"
                                size="sm"
                            />
                        @endif
                    @endif

                @if($victim->VicTalent)
                        @foreach($victim->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->talent->talentType->name=='دارنس')
                                <img
                                    src="{{ base_path('public/img/darens.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الافريقي')
                                <img
                                    src="{{ base_path('public/img/afriky.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الهلال_الاحمر')
                                <img
                                    src="{{ base_path('public/img/helal.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الكشافة')
                                <img
                                    src="{{ base_path('public/img/kashaf.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
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
                        <label class="text-red-600" >&nbsp;({{$victim->wife->otherName}})</label> ;
                    @endif
                    @if($victim->wife->Job)
                        <label>&nbsp;</label>
                        @if($victim->wife->Job->jobType->name=='القوات_المسلحة')
                            <x-filament::avatar
                                src="{{ base_path('public/img/milatary.jpg') }}"
                                size="sm"
                            />
                        @endif
                        @if($victim->wife->Job->jobType->name=='الداخلية')
                            <x-filament::avatar
                                src="{{ base_path('public/img/aladel.jpeg') }}"
                                size="sm"
                            />
                        @endif
                    @endif

                @if($victim->wife->VicTalent)
                        @foreach($victim->wife->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->talent->talentType->name=='دارنس')
                                <img
                                    src="{{ base_path('public/img/darens.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الافريقي')
                                <img
                                    src="{{ base_path('public/img/afriky.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الهلال_الاحمر')
                                <img
                                    src="{{ base_path('public/img/helal.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الكشافة')
                                <img
                                    src="{{ base_path('public/img/kashaf.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
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
                        <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label> ;
                    @endif
                    @if($victim->Job)

                        <label>&nbsp;</label>
                        @if($victim->Job->jobType->name=='القوات_المسلحة')
                            <x-filament::avatar
                                src="{{ base_path('public/img/milatary.jpg') }}"
                                size="sm"
                            />
                        @endif
                        @if($victim->Job->jobType->name=='الداخلية')
                            <x-filament::avatar
                                src="{{ base_path('public/img/aladel.jpeg') }}"
                                size="sm"
                            />

                        @endif
                    @endif
                @if($victim->VicTalent)
                        @foreach($victim->VicTalent as $talent)
                            <label>&nbsp;</label>
                            @if($talent->talent->talentType->name=='دارنس')
                                <img
                                    src="{{ base_path('public/img/darens.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الافريقي')
                                <img
                                    src="{{ base_path('public/img/afriky.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الهلال_الاحمر')
                                <img
                                    src="{{ base_path('public/img/helal.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
                            @endif
                            @if($talent->talent->talentType->name=='الكشافة')
                                <img
                                    src="{{ base_path('public/img/kashaf.jpg') }}"
                                    style="width: 20px; height: 20px;"
                                />
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


@endsection
