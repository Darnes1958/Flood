
<div class="flex ">

@if($who=='talent')
        @if($record->talentType->name=='دارنس')
            <x-filament::avatar
                src="{{ asset('img/darens.jpg') }}"
                size="md"
            />
        @endif
        @if($record->talentType->name=='الافريقي')
            <x-filament::avatar
                src="{{ asset('img/afriky.jpg') }}"
                size="md"
            />
        @endif
        @if($record->talentType->name=='الهلال_الاحمر')
            <x-filament::avatar
                src="{{ asset('img/helal.jpg') }}"
                size="md"
            />
        @endif
        @if($record->talentType->name=='الكشافة')
            <x-filament::avatar
                src="{{ asset('img/kashaf.jpg') }}"
                size="md"
            />
        @endif
@endif

@if($who=='job')
        @if($record->jobType->name=='القوات_المسلحة')
            <x-filament::avatar
                src="{{ asset('img/milatary.jpg') }}"
                size="sm"
            />
        @endif
        @if($record->jobType->name=='الداخلية')
            <x-filament::avatar
                src="{{ asset('img/aladel.jpeg') }}"
                size="sm"
            />
        @endif

    @endif


</div>
