@extends('PDF.PrnMaster4')

@section('mainrep')

    @php $print_count=0; @endphp



            <div style="position: relative;">
                @foreach($victims as $victim)

                    <div  class="flex ">


                               <img src="{{ storage_path('app/public/'.\App\Models\Country::find($victim->Familyshow->country_id)->image) }}"  style="width: 26px; height: 26px;" />

                                <label>&nbsp;</label>

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
                                    <label style="color: #6b21a8" >الزوجة :&nbsp;&nbsp; </label>
                                @endif



                        {{$victim->FullName}}
                        @if($victim->otherName)
                            <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label>
                        @endif

                        <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <label  class="text-xl"> &nbsp;العنوان : </label>
                        <label  class="text-xl">{{$victim->Street->StrName}}</label>
                    </div>


                    @if($victim->husband_id)
                            <div   class="flex">
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <label  class="text-green-500 text-xl" >زوجها : </label>
                            <label  class="text-xl">&nbsp;{{$victim->wife->FullName}}</label>
                            @if($victim->wife->otherName)
                                <label class="text-red-600 text-xl" >&nbsp;({{$victim->wife->otherName}})</label> ;
                            @endif
                            </div>
                    @endif
                    @if($victim->details)
                        <div   class="flex">
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <label class="text-yellow-700 text-xl" >&nbsp;{{$victim->details}}</label>
                        </div>
                    @endif


                @if($victim->is_mother)

                        @php
                         echo "<div class=\"flex text-xl\">";
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
                                        echo "<div class=\"flex text-git add .
                                        xl\" style=\"text-align: right;\"  >";
                                        echo "<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>";
                                        echo "<label class=\"text-sky-500\">(&nbsp;&nbsp;&nbsp;أبناءها من  :  </label><label>&nbsp;{$name2} {$name3} {$name4}) </label>";
                                    }
                                    echo "<label>&nbsp;</label>";
                                    if ($i===0) { echo "<label> &nbsp;{$item->Name1}</label>";}
                                    else {echo "<label> &nbsp;, {$item->Name1}</label>";}

                                    if($son->otherName)
                                        echo "<label class=\"text-red-600\" >&nbsp;({$son->otherName})</label> ";

                                                  $i++;

                                                }
                            }

                         echo "</div>" ;
                         @endphp

                        @endif
                     <br>

                @endforeach


            </div>



@endsection
