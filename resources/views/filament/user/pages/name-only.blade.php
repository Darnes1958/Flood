
<div class="flex ">

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

                @if($talent->talent->talentType->name=='دارنس')
                    <x-filament::avatar
                        src="{{ asset('img/darens.jpg') }}"
                        size="sm"
                    />
                @endif
                @if($talent->talent->talentType->name=='الافريقي')
                    <x-filament::avatar
                        src="{{ asset('img/afriky.jpg') }}"
                        size="sm"
                    />
                @endif
                @if($talent->talent->talentType->name=='الهلال_الاحمر')
                    <x-filament::avatar
                        src="{{ asset('img/helal.jpg') }}"
                        size="sm"
                    />
                @endif
                @if($talent->talent->talentType->name=='الكشافة')
                    <x-filament::avatar
                        src="{{ asset('img/kashaf.jpg') }}"
                        size="sm"
                    />
                @endif
            @endforeach
        @endif

    @if($record->Job)
            @if($record->Job->jobType->name=='القوات_المسلحة')
                <x-filament::avatar
                    src="{{ asset('img/milatary.jpg') }}"
                    size="sm"
                />
            @endif
            @if($record->Job->jobType->name=='الداخلية')
                <x-filament::avatar
                    src="{{ asset('img/aladel.jpeg') }}"
                    size="sm"
                />
            @endif
        @endif

</div>
