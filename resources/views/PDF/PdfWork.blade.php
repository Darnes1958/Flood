@extends('PDF.PrnMasterGuest')

@section('mainrep')

    <table style="border-collapse: collapse;
                border: none;" width="100%"   align="right" >
        <thead >
        <tr >
            <th style="width: 35%;"></th>
            <th style="width: 35%;"></th>
            <th style="width: 30%;"></th>
        </tr>
        </thead>
        <tbody >

        @foreach($victims as $victim)
            <tr style="margin-top: 40px;padding: 10px;">
                <td style="color: #bf800c">
                    <div class="flex">
                        {{$victim->FullName}}
                        <label>&nbsp;</label>
                        <img src=" {{ storage_path('app/public/'.$victim->Job->image) }}"  style="width: 26px; height: 26px;" />
                    </div>
                </td>
                <td > {{$victim->Street->StrName}}  </td>

                @if($victim->image2)
                 <td style="text-align: center">

                     <x-filament::avatar
                         src="{{  storage_path('app/public/'.$victim->image2[0]) }} "
                         size="w-20 h-20"
                     />
                 </td>
                @else
                <td>     </td>
                @endif
            </tr>


        @endforeach

        </tbody>
    </table>


@endsection







