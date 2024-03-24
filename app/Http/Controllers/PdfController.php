<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Tribe;
use App\Models\Victim;
use App\Models\Video;
use Barryvdh\DomPDF\Facade\Pdf;
use Cohensive\OEmbed\Facades\OEmbed;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
  public function getVideo(Video $video)
  {
    $name1 = $video->attachment;
    $name =storage_path() ."/app/public/".$video->attachment;


    $headers = array(
      'Content-type'          => 'video/mp4',
      'Content-Disposition'   => 'inline; filename="' . $name . '"'
    );
    return Response::make( file_get_contents($name), 200, $headers);
  }
  public function setVideo(Video $video)
  {
    $name = 'https://www.youtube.com/watch?v=mCOOddO4kkY';
    $embed = OEmbed::get($name);
    return $embed;
  }
  public function PdfFamily($family_id){

   $fam=Family::find($family_id);
   $family_name=$fam->FamName;

   $tribe_name=Tribe::find($fam->tribe_id)->TriName;

   $count=Victim::where('family_id',$family_id)->count();
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
       ->Where(function ( $query) {
           $query->where('is_father',null)
               ->orwhere('is_father',0);
       })
       ->Where(function ( $query) {
           $query->where('is_mother',null)
               ->orwhere('is_mother',0);
       })
     ->where('husband_id',null)
     ->where('wife_id',null)
     ->where('father_id',null)

     ->get();



   $html = view('PDF.PdfVictimFamily',
     ['victim_father'=>$victim_father,'victim_mother'=>$victim_mother,
       'victim_husband'=>$victim_husband,'victim_wife'=>$victim_wife,
       'victim_only'=>$victim_only,
       'tribe_name'=>$tribe_name,'count'=>$count,
       'family_name'=>$family_name])->toArabicHTML();

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
