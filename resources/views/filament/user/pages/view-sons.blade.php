<div >
    @if($record->father->count()>0)
        <div class="flex">
            <p style="color: aqua;font-weight: bold">أبناءه :&nbsp;</p>
            @php
                $i=0;
                foreach($record->father as $item){
                     if ($i == 0) echo "<p>{$item->Name1}</p>"; else echo "<p style=\"color: aqua;font-weight: bold\">&nbsp;,&nbsp;</p><p>{$item->Name1}</p>";
                     $i++;
                     if ($i == 4) break;}
            @endphp
        </div>
        <div class="flex">

            @php
                $ii=0;
                foreach($record->father as $item){
                    if ($ii<4) {$ii++; continue;}
                     if ($ii == 4) echo "<p>{$item->Name1}</p>"; else echo "<p style=\"color: aqua;font-weight: bold\">&nbsp;,&nbsp;</p><p>{$item->Name1}</p>";
                     $ii++;}
            @endphp
        </div>

    @endif
    @if($record->mother->count()>0)
            <div class="flex">
                <p style="color: aqua;font-weight: bold">أبناءها :&nbsp;</p>
                @php
                        $i=0;
                        foreach($record->mother as $item){
                             if ($i == 0) echo "<p>{$item->Name1}</p>"; else echo "<p style=\"color: aqua;font-weight: bold\">&nbsp;,&nbsp;</p><p>{$item->Name1}</p>";
                             $i++;
                             if ($i == 4) break;}
                 @endphp
            </div>
            <div class="flex">

                @php
                    $ii=0;
                    foreach($record->mother as $item){
                        if ($ii<4) {$ii++; continue;}
                         if ($ii == 4) echo "<p>{$item->Name1}</p>"; else echo "<p style=\"color: aqua;font-weight: bold\">&nbsp;,&nbsp;</p><p>{$item->Name1}</p>";
                         $ii++;}
                @endphp
            </div>
        @if(!$record->wife)
            <div class="flex">
                <p>&nbsp;&nbsp;(من : &nbsp; {{$item->Name2}}&nbsp;{{$item->Name3}}&nbsp;{{$item->Name4}})</p>
            </div>
        @endif

    @endif

</div>
