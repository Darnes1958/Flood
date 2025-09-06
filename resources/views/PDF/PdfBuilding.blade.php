@extends('PDF.PrnMasterGuest')

@section('mainrep')

    <table style="border-collapse: collapse;
                border: none;" width="100%"   align="right" >
        <thead >
        <tr >
            <th style="width: 40%;"></th>
            <th style="width: 20%;">عدد الشهداء بالعمارة</th>
            <th style="width: 30%;"></th>

        </tr>
        </thead>
        <tbody >

        @foreach($streets as $street)
            <tr style="margin-top: 40px;padding: 20px;">
                <td style="color: #bf800c">{{$street->StrName}}</td>
                <td style="text-align: center">{{$street->victim_count}}</td>

                 <td style="text-align: center">
                     @if($street->image)
                     <x-filament::avatar :circular="false"
                         src="{{  storage_path('app/public/'.$street->image[0]) }} "
                         size="w-24 h-24"
                     />
                     @endif
                 </td>

            </tr>


        @endforeach

        </tbody>
    </table>


@endsection







