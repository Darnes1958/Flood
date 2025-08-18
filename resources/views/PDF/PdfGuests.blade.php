@extends('PDF.PrnMasterGuest')

@section('mainrep')

    <table style="border-collapse: collapse;
                border: none;" width="100%"   align="right" >
        <thead >
        <tr >
            <th style="width: 30%;"></th>
            <th style="width: 40%;"></th>
            <th style="width: 30%;"></th>
        </tr>
        </thead>
        <tbody >

        @foreach($victims as $victim)
            <tr style="margin-top: 40px;padding: 10px;">
                <td style="color: #bf800c">{{$victim->FullName}}</td>
                <td > {{$victim->notes}}  </td>

                @if($victim->image)
                 <td style="text-align: center">

                     <x-filament::avatar
                         src="{{  storage_path('app/public/'.$victim->image) }} "
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







