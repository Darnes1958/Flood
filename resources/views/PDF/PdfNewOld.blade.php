@extends('PDF.PrnCont')

@section('mainrep')
    <div style="position: relative;">
        <div style="text-align: center;font-size: 14pt;">
            <label>شهداء فيضان وادي درنه</label>
        </div>
        <br>
        <div style="text-align: center;font-size: 12pt;">
            @if($what=='newData' )
                <label  > كشف بالغير مبلغ عنهم في ملفات النيابة الجديدة  </label>
            @endif
            @if($what=='oldData' )
                <label  > كشف بالغير مبلغ عنهم في ملفات النيابة القديمة  </label>
            @endif
            @if($what=='allData' )
                    <label  > كشف بالغير مبلغ عنهم في جميع ملفات النيابة  </label>
            @endif
            @if($what=='oldNotnewData' )
                    <label  > كشف بالمبلغ عنهم في ملفات النيابة القديمة وغير مبلغ عنهم فالقديمة  </label>
            @endif

        </div>
    </div>
    <table  width="100%"   align="right" >
        <thead style="  margin-top: 8px;">
        <tr style="background:lightgray">

            <th style="width: 10%;">الاسم</th>
            <th style="width: 10%;">العائلة</th>
            <th style="width: 6%;">الرقم الألي</th>
            <th style="width: 6%;">ت</th>
        </tr>
        </thead>
        <tbody id="addRow" class="addRow">

        @foreach($TableName as $key=>$item)
            <tr class="font-size-12">


                <td > {{$item->FullName}}  </td>
                <td style="text-align: center"> {{$item->Family->FamName}}  </td>
                <td style="text-align: center"> {{$item->id}}  </td>
                <td style="text-align: center"> {{$key+1}}  </td>

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







