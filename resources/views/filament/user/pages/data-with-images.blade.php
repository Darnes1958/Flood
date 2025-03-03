<div>
<div class="flex ">

            <p  >{{$record->FullName}}</p>
    @if ($record->otherName) <p style="color: #9f1239;font-weight: bold"> &nbsp; [{{$record->otherName}}]&nbsp; </p> @endif
    <div>&nbsp;&nbsp;</div>
        @if($record->VicTalent)
            @foreach($record->VicTalent as $talent)
                <label>&nbsp;</label>
                @if($talent->Talent->image)

                    <x-filament::avatar
                        src="{{  asset($talent->Talent->image) }} "
                        size="sm"
                    />
                @endif
            @endforeach
        @endif
        @if($record->Job)
            @if($record->Job->image)
                <label>&nbsp;</label>
                <img src="{{ asset($record->Job->image) }}"  style="width: 20px; height: 20px;" />
            @endif
        @endif

</div>

</div>
