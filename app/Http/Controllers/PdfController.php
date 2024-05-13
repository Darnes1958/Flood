<?php

namespace App\Http\Controllers;

use App\Models\Allview;
use App\Models\Bedmafview;
use App\Models\Family;
use App\Models\Tasbedview;
use App\Models\Tasmafview;
use App\Models\Tasreeh;
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

  public function PdfTekrar($what){
      if ($what=='inTasAndBed')   $TableName = Tasbedview::query()->orderBy('nameTas')->get();
      if ($what=='inTasAndMaf')   $TableName = Tasmafview::query()->orderBy('nameTas')->get();
      if ($what=='inBedAndMaf')   $TableName = Bedmafview::query()->orderBy('nameBed')->get();
      if ($what=='inAll')   $TableName = Allview::query()->orderBy('nameTas')->get();
      $html = view('PDF.PdfTekrar',
          ['TableName'=>$TableName,'what'=>$what])->toArabicHTML();
      $pdf = Pdf::loadHTML($html)->output();
      $headers = array(
          "Content-type" => "application/pdf",
      );
      return response()->streamDownload(
          fn () => print($pdf),
          "tekrar.pdf",
          $headers
      );
  }
    public function PdfRepeted($what){
        if ($what=='inTas')   $TableName = Tasreeh::where('repeted',1)->orderBy('name')->get();
        if ($what=='inMaf')   $TableName = Tasmafview::where('repeted',1)->orderBy('name')->get();
        if ($what=='inBed')   $TableName = Bedmafview::where('repeted',1)->orderBy('name')->get();


        $html = view('PDF.PdfRepeted',
            ['TableName'=>$TableName,'what'=>$what])->toArabicHTML();

        $pdf = Pdf::loadHTML($html)->output();
        $headers = array(
            "Content-type" => "application/pdf",
        );
        return response()->streamDownload(
            fn () => print($pdf),
            "repeted.pdf",
            $headers
        );

    }
  public function PdfFamily($family_id){

   $fam=Family::find($family_id);
   $family_name=$fam->FamName;

   $tribe_name=Tribe::find($fam->tribe_id)->TriName;

   $count=Victim::where('family_id',$family_id)->count();
   $victim_father=Victim::with('father')
     ->where('family_id',$family_id)
     ->where('is_father','1')->get();

   $fathers=Victim::where('family_id',$family_id)->where('is_father',1)->select('id');
    $mothers=Victim::where('family_id',$family_id)->where('is_mother',1)->select('id');

   $victim_mother=Victim::with('mother')
     ->where('family_id',$family_id)
     ->where('is_mother','1')
     ->where(function ( $query) use($fathers) {
       $query->where('husband_id', null)
         ->orwhere('husband_id', 0)
         ->orwhereNotIn('husband_id',$fathers);
     })

     ->get();

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
     ->where(function ( $query) use($mothers) {
       $query->where('mother_id', null)
         ->orwhere('mother_id', 0)
         ->orwhereNotIn('mother_id',$mothers);
     })


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
