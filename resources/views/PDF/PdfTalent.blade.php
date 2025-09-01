@extends('PDF.PrnMasterGuest')

@section('mainrep')

    <div class="flex flex-row  justify-center items-center">
        <p class="text-4xl text-blue-500"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;فنانون وأدباء</p>
    </div>

     <div class="flex flex-row  justify-center items-center mb-8">
         @foreach($talents as $talent )
             @if($talent->id==3 || $talent->id==2) @continue @endif
             <x-filament::avatar :circular="false"
                                 src="{{  storage_path('app/public/'.$talent->image) }} "
                                 size="w-6 h-6"  />
         @endforeach

     </div>




    </table>

    <table style="border-collapse: collapse;
                border: none;" width="100%"   align="right" >
        <thead >
        <tr >

            <th style="width: 70%;"></th>
            <th style="width: 30%;"></th>

        </tr>
        </thead>
        <tbody >

        @foreach($victims as $victim)
            @php $preName=\App\Models\Talent::find($victim->talent_id)->preName @endphp
            <tr style="margin-top: 40px;padding: 20px;">
                <td >
                 <div class="flex">
                     <label  style="color: #0000ff;margin-inline-end: 20px;" >{{ $preName}}</label>
                     <label  style="color: #bf800c">  {{$victim->Victim->FullName}} </label>
                 </div>

                </td>


                 <td style="text-align: center">
                     @if($victim->Victim->image2)
                     <x-filament::avatar :circular="false"
                         src="{{  storage_path('app/public/'.$victim->Victim->image2[0]) }} "
                         size="w-28 h-28"
                     />
                     @endif
                 </td>

            </tr>


        @endforeach

        </tbody>
    </table>


@endsection







