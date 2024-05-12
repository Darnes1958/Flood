@extends('PDF.PrnCont')

@section('mainrep')
    <div style="position: relative;">
        <div style="text-align: center;font-size: 14pt;">

            <label>شهداء فيضان وادي درنه</label>
        </div>
        <br>
        <div style="text-align: center;font-size: 12pt;">
            @if($what=='inTas')
            <label  > كشف بالتداخل في (بتصريح)  </label>
            @endif
                @if($what=='inBed')
                    <label  > كشف بالتداخل في (بدون تصريح)  </label>
                @endif
                @if($what=='inMaf')
                    <label  > كشف بالتداخل في (مفقودين)  </label>
                @endif

        </div>
    </div>
    <table  width="100%"   align="right" >
        <thead style="  margin-top: 8px;">
        <tr style="background:lightgray">
            <th style="width: 40%;">الاسم</th>
            <th style="width: 20%;">العائلة</th>
            <th style="width: 10%;">ت</th>
        </tr>
        </thead>
        <tbody id="addRow" class="addRow">
        @php($ser=1)
        @foreach($TableName as $key=>$item)
            <tr class="font-size-12">
                <td>{{$item->name}}</td>
                <td style="text-align: center"> {{$item->Family->FamName}}  </td>
                <td style="text-align: center"> {{$ser}}  </td>
            </tr>
            <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
                <label class="page"></label>
                <label> صفحة رقم </label>
            </div>
         @php($ser++)
        @endforeach

        </tbody>
    </table>


@endsection







