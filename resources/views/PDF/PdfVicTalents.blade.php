@extends('PDF.PrnMasterGuest')

@section('mainrep')

    <table style="border-collapse: collapse;
                border: none;" width="100%"   align="right" >
        <thead >
        <tr >
            <th style="width: 50%;"></th>
            <th style="width: 50%;"></th>

        </tr>
        </thead>
        <tbody >

        @foreach($victims as $victim)
            <tr style="margin-top: 40px;padding: 20px;">
                <td style="color: #bf800c">{{$victim->Victim->FullName}}</td>


                 <td style="text-align: center">

                     <x-filament::avatar :circular="false"
                         src="{{  storage_path('app/public/'.$victim->Victim->image2[0]) }} "
                         size="w-24 h-24"
                     />
                 </td>

            </tr>


        @endforeach

        </tbody>
    </table>


@endsection







