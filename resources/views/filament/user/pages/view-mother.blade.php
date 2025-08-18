<div class="flex te text-lg">
    @if($record->sonOfMother)
        @if(\App\Models\Victim::find($record->id)->male=='ذكر')
            <p style="color: #00bb00;font-weight: bold">والدته :&nbsp;</p>
        @else
            <p style="color: #00bb00;font-weight: bold">والدتها :&nbsp;</p>
        @endif

        <p >{{$record->sonOfMother->FullName}}</p>
            @if($record->sonOfMother->Familyshow->country_id!=$record->Familyshow->country_id)
                <label>&nbsp;</label>

                <img src="{{ asset('storage/'.\App\Models\Country::find($record->sonOfMother->Familyshow->country_id)->image) }}"  style="width: 30px; height: 30px;" />
            @endif

    @endif

</div>
