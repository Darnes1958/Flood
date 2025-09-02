@extends('PDF.PrnMaster4')

@section('mainrep')

    @php $print_count=0; $ser=0@endphp




            <div style="position: relative;">
                @foreach($victims as $victim)
                    @if($victim->father_id!=null && (!$victim->is_father && $victim->wife_id==null)
                            && (!$victim->is_mother && $victim->husband_id==null )
                            )
                        @continue;
                    @endif

                <div class="flex">
                   <div >
                        <div class="flex">
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
                                    <label style="color: #6b21a8" >الزوجة :&nbsp;&nbsp; </label>
                                @endif

                            @endif
                            {{$victim->FullName}}
                            @if($victim->otherName)
                                <label class="text-red-600" >&nbsp;({{$victim->otherName}})</label>
                            @endif
                        </div>
                        <div class="flex">
                            <label  > &nbsp;العنوان : </label>
                            <label  >{{$victim->Street->StrName}}</label>
                        </div>

                       @if($victim->sonOfFather)
                           <div   class="flex">
                               <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                               @if($victim->male=='ذكر')
                                   <label  class="text-green-500">والده : </label>
                               @else
                                   <label  class="text-green-500">والدها : </label>
                               @endif

                               <label  >&nbsp;{{$victim->sonOfFather->FullName}}</label>
                               @if($victim->sonOfFather->otherName)
                                   <label class="text-red-600" >&nbsp;({{$victim->sonOfFather->otherName}})</label> ;
                               @endif


                           </div>
                       @endif
                       @if($victim->sonOfMother)
                           <div   class="flex">
                               <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                               @if($victim->male=='ذكر')
                                   <label  class="text-green-500">والدته : </label>
                               @else
                                   <label  class="text-green-500">والدتها : </label>
                               @endif

                               <label  >&nbsp;{{$victim->sonOfMother->FullName}}</label>
                               @if($victim->sonOfMother->Familyshow->country_id!=$victim->Familyshow->country_id)
                                   <label>&nbsp;</label>
                                   <img src="{{ storage_path('app/public/'.\App\Models\Country::find($victim->sonOfMother->Familyshow->country_id)->image) }}"  style="width: 26px; height: 26px;" />

                               @endif
                               @if($victim->sonOfMother->otherName)
                                   <label class="text-red-600" >&nbsp;({{$victim->sonOfMother->otherName}})</label> ;
                               @endif


                           </div>
                       @endif
                       @if($victim->wife_id)
                           <div   class="flex">
                               <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>

                               <label  class="text-green-500">زوجته : </label>


                               <label  >&nbsp;{{$victim->husband->FullName}}</label>
                               @if($victim->husband->otherName)
                                   <label class="text-red-600" >&nbsp;({{$victim->husband->otherName}})</label> ;
                               @endif
                               @if($victim->husband->Familyshow->country_id!=$victim->Familyshow->country_id)
                                   <label>&nbsp;</label>
                                   <img src="{{ storage_path('app/public/'.\App\Models\Country::find($victim->husband->Familyshow->country_id)->image) }}"  style="width: 26px; height: 26px;" />

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
                               @if($victim->wife->Familyshow->country_id!=$victim->Familyshow->country_id)
                                   <label>&nbsp;</label>
                                   <img src="{{ storage_path('app/public/'.\App\Models\Country::find($victim->wife->Familyshow->country_id)->image) }}"  style="width: 26px; height: 26px;" />

                               @endif


                           </div>

                       @endif
                       @if($victim->is_father)
                           <div   class="flex">
                               <div  style="text-align: right;" class="flex">
                                   <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                   <label class=" text-sky-500" >&nbsp;الأبناء :&nbsp; </label>
                                   @if($victim->father->first()->Familyshow->country_id!=$victim->Familyshow->country_id)
                                       <label>&nbsp;</label>
                                       <img src="{{ storage_path('app/public/'.\App\Models\Country::find($victim->husband->Familyshow->country_id)->image) }}"  style="width: 26px; height: 26px;" />

                                   @endif
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
                                    if($victim->mother->first()->Familyshow->country_id!=$victim->Familyshow->country_id) {
                                       echo   "<label>&nbsp;</label>";
                                       echo   "<img src=". storage_path('app/public/'.\App\Models\Country::find($victim->mother->first()->Familyshow->country_id)->image) ."  style=\"width: 26px; height: 26px;\" />";

                                      }

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
                                              echo "<div class=\"flex\" style=\"text-align: right;\"  >";
                                              echo "<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>";
                                              echo "<label class=\"text-sky-500\">(&nbsp;&nbsp;&nbsp;أبناءها من  :  </label><label>&nbsp;{$name2} {$name3} {$name4}) </label>";
                                          }
                                          echo "<label>&nbsp;</label>";
                                          if ($i===0) { echo "<label> &nbsp;{$item->Name1}</label>";}
                                          else {echo "<label> &nbsp;, {$item->Name1}</label>";}

                                          if($item->otherName)
                                              echo "<label class=\"text-red-600\" >&nbsp;({$item->otherName})</label> ";

                                          if($item->Job)

                                                        $i++;

                                                      }
                                  }
                               echo "</div>" ;
                           @endphp

                       @endif
                   </div>




                        @if($victim->image2)
                            <div class="mr-12 mb-4">
                                <x-filament::avatar :circular="false" src="{{  storage_path('app/public/'.$victim->image2[0]) }} " size="w-24 h-24"  />
                            </div>

                        @endif
                </div>

                    @if($victim->is_great_grandfather || $victim->is_grandfather
                               || $victim->is_father || $victim->is_great_grandmother || $victim->is_grandmother
                               || $victim->is_mother || $victim->husband_id || $victim->wife_id || $victim->sonOfMother || $victim->sonOfFather) <br> @endif

                @endforeach


            </div>



@endsection
