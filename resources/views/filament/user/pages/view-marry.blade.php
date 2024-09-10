<div class="flex te">
    @if($record->wife)
      <p style="color: #fbbf24;font-weight: bold">وزوجها :&nbsp;</p>
      <p >{{$record->wife->FullName}}</p>
    @endif
    @if($record->husband)
        <p style="color: #00bb00;font-weight: bold">وزوجته :&nbsp;</p>
        <p >{{$record->husband->FullName}}</p>
    @endif

</div>
