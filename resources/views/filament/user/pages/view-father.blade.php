<div class="flex te text-lg">
    @if($record->sonOfFather)
        @if($record->male=='ذكر')
            <p style="color: #fbbf24;font-weight: bold">والده :&nbsp;</p>
        @else
          <p style="color: #fbbf24;font-weight: bold">والدها :&nbsp;</p>
        @endif

      <p >{{$record->sonOfFather->FullName}}</p>
    @endif

</div>
