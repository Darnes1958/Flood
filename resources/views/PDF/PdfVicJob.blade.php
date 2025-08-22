@extends('PDF.PrnMasterGuest')

@section('mainrep')
    <table>
        <thead>
         <tr style="width: 25%"></tr>
         <tr style="width: 25%"></tr>
         <tr style="width: 50%"></tr>
        </thead>
        <td><p class="text-4xl"> </p></td>
        <td>        <x-filament::avatar :circular="false"
                                        src="{{  storage_path('app/public/'.$job->image) }} "
                                        size="w-20 h-20"
            />
        </td>
        <td ><p class="text-4xl text-blue-500"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$job->name}}</p></td>
    </table>

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
                <td style="color: #bf800c">{{$victim->FullName}}</td>


                 <td style="text-align: center">
                     @if($victim->image2)
                     <x-filament::avatar :circular="false"
                         src="{{  storage_path('app/public/'.$victim->image2[0]) }} "
                         size="w-24 h-24"
                     />
                     @endif
                 </td>

            </tr>


        @endforeach

        </tbody>
    </table>


@endsection







