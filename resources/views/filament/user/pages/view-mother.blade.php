<div class="flex te text-lg">
    @if($record->sonOfMother)
        @if(\App\Models\Victim::find($record->id)->male=='ذكر')
            <p style="color: #00bb00;font-weight: bold">والدته :&nbsp;</p>
        @else
            <p style="color: #00bb00;font-weight: bold">والدتها :&nbsp;</p>
        @endif

        <p >{{$record->sonOfMother->FullName}}</p>
    @endif

</div>
