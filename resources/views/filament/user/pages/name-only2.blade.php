
<div  class="flex text-xl">

    @if($record->male='ذكر')
        @if($record->is_great_grandfather)
            <p style="color: aqua; ">جد الأب :&nbsp;&nbsp;</p>
        @else
            @if($record->is_grandfather)
                <p style="color: aqua; ">الجد : &nbsp;&nbsp;</p>
            @else
                @if($record->is_father)
                    <p style="color: aqua; ">الأب : &nbsp;&nbsp;</p>
                @endif
            @endif
        @endif
    @endif
        @if($record->male='أنثي')
            @if($record->is_great_grandmother)
                <p style="color: aqua; ">جدة الأب :&nbsp;&nbsp;</p>
            @else
                @if($record->is_grandmother)
                    <p style="color: aqua; ">الجدة : &nbsp;&nbsp;</p>
                @else
                    @if($record->is_mother)
                        <p style="color: aqua; ">الأم : &nbsp;&nbsp;</p>
                    @endif
                @endif
            @endif
        @endif

    @if($record->is_father)
     <p style="color: #fbbf24; ">{{$record->FullName}}&nbsp;&nbsp;</p>
    @else
        @if($record->is_mother)
            <p style="color: #00bb00;">{{$record->FullName}}&nbsp;&nbsp;</p>
        @else
            <p  >{{$record->FullName}}&nbsp;&nbsp;</p>
        @endif
    @endif

        @if($record->VicTalent)
            @foreach($record->VicTalent as $talent)
                <label>&nbsp;</label>
                @if($talent->Talent->image)

                    <x-filament::avatar
                        src="{{  asset('storage/'.$talent->Talent->image) }} "
                        size="sm"
                    />
                @endif
            @endforeach
        @endif
        @if($record->Job)
            @if($record->Job->image)
                <label>&nbsp;</label>
                <img src="{{ asset('storage/'.$record->Job->image) }}"  style="width: 20px; height: 20px;" />
            @endif
        @endif


</div>
