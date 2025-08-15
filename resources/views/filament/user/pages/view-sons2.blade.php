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

                @php
                    if ($record->has_more !=1) {
                        echo "<div class=\"flex\">
                            <p style=\"color: aqua;font-weight: bold\">أبناءها :&nbsp;</p>";
                        $i=0;
                        foreach($record->mother as $item){
                             if ($i == 0) echo "<p>{$item->Name1}</p>"; else echo "<p style=\"color: aqua;font-weight: bold\">&nbsp;,&nbsp;</p><p>{$item->Name1}</p>";
                             $i++;}
                        if (!$record->wife) echo "</div><div class=\"flex\"> <p>&nbsp&nbsp;(من : &nbsp {$item->Name2}&nbsp;{$item->Name3}&nbsp;{$item->Name4})</p>";
                        echo "</div>";
                    } else {
                        echo "<div class=\"flex\">
                            <p style=\"color: aqua;font-weight: bold\">أبناءها :&nbsp;</p> </div> <div class=\"flex\"> ";
                        $rec=\App\Models\Victim::where('mother_id',$record->id)->orderby('Name2')->get();
                        $i=0;

                        $name2=$rec[0]->Name2;
                        $name3=$rec[0]->Name3;
                        $name4=$rec[0]->Name4;
                        foreach($rec as $item){
                          if ($name2 != $item->Name2){
                              echo "</div><div class=\"flex\"> <p style=\"color: blue;\">&nbsp&nbsp;(من :  {$name2}&nbsp;{$name3}&nbsp;{$name4})</p>  </div> <div class=\"flex\"> ";
                              $name2=$item->Name2;
                              $name3=$item->Name3;
                              $name4=$item->Name4;
                              $i=0;
                          }

                             if ($i == 0) echo "<p>&nbsp;{$item->Name1}</p>"; else echo "<p style=\"color: aqua;font-weight: bold\">&nbsp;,&nbsp;</p><p>{$item->Name1}</p>";
                             $i++;
                        }
                            echo "</div> <div class=\"flex\"> <p style=\"color: red;\">&nbsp&nbsp;(من :  {$name2}&nbsp;{$name3}&nbsp;{$name4})</p>  </div>";
                    }

                @endphp




    @endif

</div>
