<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Victim;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
 public function PdfFamily($family_id){

   $family_name=Family::find($family_id)->FamName;
   $victim_father=Victim::with('father')
     ->where('family_id',$family_id)
     ->where('is_father','1')->get();

   $victim_mother=Victim::with('mother')
     ->where('family_id',$family_id)
     ->where('is_mother','1')->get();

   $victim_husband=Victim::
     where('family_id',$family_id)
     ->where('male','ذكر')
     ->where('is_father','0')
     ->where('wife_id','!=',null)
     ->get();

   $victim_wife=Victim::
   where('family_id',$family_id)
     ->where('male','أنثي')
     ->where('is_mother','0')
     ->where('husband_id','!=',null)
     ->get();
   $victim_only=Victim::
   where('family_id',$family_id)
     ->where('is_father',null)
     ->where('is_mother',null)
     ->where('husband_id',null)
     ->where('wife_id',null)
     ->where('father_id',null)
     ->where('mother_id',null)
     ->get();

   $html = view('PDF.PdfVictimFamily',
     ['victim_father'=>$victim_father,'victim_mother'=>$victim_mother,
       'victim_husband'=>$victim_husband,'victim_wife'=>$victim_wife,

       'victim_only'=>$victim_only,'family_name'=>$family_name])->toArabicHTML();

   $pdf = Pdf::loadHTML($html)->output();
   $headers = array(
     "Content-type" => "application/pdf",
   );
   return response()->streamDownload(
     fn () => print($pdf),
     "victim.pdf",
     $headers
   );


 }
}
