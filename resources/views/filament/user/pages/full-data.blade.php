<div class="flex te">
    @if($record->is_father)
     <p style="color: #fbbf24; ">{{$record->FullName}}</p>
    @else
        @if($record->is_mother)
            <p style="color: #00bb00;">{{$record->FullName}}</p>
        @else
            <p  >{{$record->FullName}}</p>
        @endif
    @endif

    @if($record->wife)
      <p style="color: #fbbf24;font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;وزوجها :&nbsp;</p>
      <p >{{$record->wife->FullName}}</p>
    @endif
    @if($record->husband)
        <p style="color: #00bb00;font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;وزوجته :&nbsp;</p>
        <p >{{$record->husband->FullName}}</p>
    @endif
    @if($record->sonOfFather)
        @if($record->male=='ذكر')
        <p style="color: dodgerblue;font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ووالده :&nbsp;</p>
        @else
        <p style="color: dodgerblue;font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ووالدها :&nbsp;</p>
        @endif
        <p >{{$record->sonOfFather->FullName}}</p>
    @endif
    @if($record->sonOfMother)
        @if($record->male=='ذكر')
            <p style="color: #c084fc;font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;ووالدته :&nbsp;</p>
        @else
            <p style="color: #c084fc;font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;ووالدتها :&nbsp;</p>
        @endif

        <p >{{$record->sonOfMother->FullName}}</p>
    @endif
    @if($record->father->count()>0)
            <p style="color: aqua;font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;وأبناءه :&nbsp;</p>
        @php
            $i=0;
            foreach($record->father as $item){
                 if ($i == 0) echo "<p>{$item->Name1}</p>"; else echo "<p style=\"color: aqua;font-weight: bold\">&nbsp;,&nbsp;</p><p>{$item->Name1}</p>";
                 $i++;}
        @endphp
    @endif
    @if($record->mother->count()>0)
                <p style="color: aqua;font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;وأبناءها :&nbsp;</p>
            @php
                $i=0;
                foreach($record->mother as $item){
                     if ($i == 0) echo "<p>{$item->Name1}</p>"; else echo "<p style=\"color: aqua;font-weight: bold\">&nbsp;,&nbsp;</p><p>{$item->Name1}</p>";
                     $i++;}
                if (!$record->wife) echo "<p>&nbsp&nbsp;(من : &nbsp {$item->Name2}&nbsp;{$item->Name3}&nbsp;{$item->Name4})</p>"
            @endphp
        @endif
</div>
