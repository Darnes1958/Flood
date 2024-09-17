
<div class="flex ">


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

</div>
