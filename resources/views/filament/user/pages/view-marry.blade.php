<div class="flex te text-lg">
    @if($record->wife)
      <p style="color: #fbbf24;font-weight: bold">وزوجها :&nbsp;</p>
      <p >{{$record->wife->FullName}}</p>
        @if($record->wife->Familyshow->country_id!=1)
            <label>&nbsp;</label>

            <img src="{{ asset('storage/'.\App\Models\Country::find($record->wife->Familyshow->country_id)->image) }}"  style="width: 30px; height: 30px;" />
        @endif

    @endif
    @if($record->husband)
        <p style="color: #00bb00;font-weight: bold">وزوجته :&nbsp;</p>
        <p >{{$record->husband->FullName}}</p>
            @if($record->husband->Familyshow->country_id!=1)
                <label>&nbsp;</label>

                <img src="{{ asset('storage/'.\App\Models\Country::find($record->husband->Familyshow->country_id)->image) }}"  style="width: 30px; height: 30px;" />
            @endif

        @endif

</div>
