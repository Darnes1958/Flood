
<div class="flex ">

@if($who=='talent')
       @php
        $img=\App\Models\Talent::where('talentType',$record->talentType->value)->where('image','!=',null)->first();
       @endphp
    @if($img)
            <x-filament::avatar
                src="{{  asset($img->image) }} "
                size="sm"
                :circular="false"
            />
        @endif
@endif

@if($who=='job')
        @php
            $img=\App\Models\Job::where('jobType',$record->jobType->value)->where('image','!=',null)->first();
        @endphp
        @if($img)
            <x-filament::avatar
                src="{{  asset($img->image) }} "
                size="sm"
                :circular="false"
            />
        @endif

    @endif


</div>
