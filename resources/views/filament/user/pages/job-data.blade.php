<div class="flex">
    @if($record->Job)
            @if($record->Job->jobType->name!='القوات_المسلحة' )
                <p >{{$record->Job->name}}&nbsp;</p>
            @endif

            @if($record->Job->jobType->name=='القوات_المسلحة')
                <p style="color: #fbbf24; ">{{$record->Job->name}}&nbsp;</p>
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
