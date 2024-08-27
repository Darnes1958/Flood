@extends('PDF.PrnCont')

@section('mainrep')
    <div style="position: relative;">
        <div style="text-align: center;font-size: 14pt;">
            <label>شهداء فيضان وادي درنه</label>
        </div>
        <br>
        <div style="text-align: center;font-size: 12pt;">
            <label  > كشف بالتداخل  </label>
        </div>
    </div>
    <table  width="100%"   align="right" >
        <thead style="  margin-top: 8px;">
        <tr style="background:lightgray">

            @if($what=='inTasAndMaf' || $what=='inBedAndMaf' || $what=='inAll')
              <th style="width: 28%;">مفقودين</th>
            @endif
            @if($what=='inTasAndBed' || $what=='inBedAndMaf' || $what=='inAll')
                <th style="width: 28%;">بدون تصريح</th>
            @endif
            @if($what=='inTasAndBed' || $what=='inTasAndMaf' || $what=='inAll')
              <th style="width: 28%;">بتصريح</th>
            @endif
                @if($what=='inDedAndBal' )
                    <th style="width: 28%;">متوفيين</th>
                    <th style="width: 28%;">بلاغات</th>
                @endif

            <th style="width: 10%;">العائلة</th>
            <th style="width: 6%;">ت</th>
        </tr>
        </thead>
        <tbody id="addRow" class="addRow">

        @foreach($TableName as $key=>$item)
            <tr class="font-size-12">
                @if($what=='inTasAndMaf' || $what=='inBedAndMaf' || $what=='inAll')
                <td>{{$item->nameMaf}}</td>
                @endif
                    @if($what=='inTasAndBed' || $what=='inBedAndMaf' || $what=='inAll')
                <td>{{$item->nameBed}}</td>
                    @endif
                    @if($what=='inTasAndBed' || $what=='inTasAndMaf' || $what=='inAll')
                          <td>{{$item->nameTas}}</td>
                    @endif
                    @if($what=='inDedAndBal' )
                        <td>{{$item->nameDed}}</td>
                        <td>{{$item->nameBal}}</td>
                    @endif

                <td style="text-align: center"> {{$item->FamName}}  </td>
                <td style="text-align: center"> {{$item->id}}  </td>
            </tr>
            <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
                <label class="page"></label>
                <label> صفحة رقم </label>
            </div>

        @endforeach

        </tbody>
    </table>


@endsection







