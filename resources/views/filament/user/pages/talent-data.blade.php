<div class="flex">
    @if($record->VicTalent)
        @foreach($record->VicTalent as $talent)
            @if($talent->talent->talentType->name=='مواهب')
                <p >{{$talent->talent->name}}&nbsp;</p>

            @endif

            @if($talent->talent->talentType->name=='دارنس')
                <p style="color: #fbbf24; ">{{$talent->talent->name}}&nbsp;</p>
                <x-filament::avatar
                    src="{{ asset('img/darens.jpg') }}"

                    size="sm"
                />
            @endif
            @if($talent->talent->talentType->name=='الافريقي')
                <p style="color: #00bb00; ">{{$talent->talent->name}}&nbsp;</p>
                <x-filament::avatar
                    src="{{ asset('img/afriky.jpg') }}"

                    size="sm"
                />
            @endif
                @if($talent->talent->talentType->name=='الهلال_الاحمر')
                    <p style="color: #9f1239; ">{{$talent->talent->name}}&nbsp;</p>
                    <x-filament::avatar
                        src="{{ asset('img/helal.jpg') }}"

                        size="sm"
                    />
                @endif
                @if($talent->talent->talentType->name=='الكشافة')
                    <p style="color: #3f6212; ">{{$talent->talent->name}}&nbsp;</p>
                    <x-filament::avatar
                        src="{{ asset('img/kashaf.jpg') }}"

                        size="sm"
                    />
                @endif
        @endforeach
    @endif

</div>
