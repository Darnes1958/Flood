<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(\App\Http\Controllers\PdfController::class)->group(function (){
  route::get('/pdffamily/{family_id?},{bait_id?}', 'PdfFamily')->name('pdffamily') ;
  route::get('/pdffamilyshow/{familyshow_id?}', 'PdfFamilyShow')->name('pdffamilyshow') ;
  route::get('/pdftekrar/{what}', 'PdfTekrar')->name('pdftekrar') ;
  route::get('/pdfrepeted/{what}', 'PdfRepeted')->name('pdfrepeted') ;
  route::get('/pdfnewold/{what?},{libya?}', 'PdfNewOld')->name('pdfnewold') ;
  Route::get('get-video/{video}', 'getVideo')->name('getVideo');
  Route::get('set-video/{video}', 'setVideo')->name('setVideo');
});
Route::controller(\App\Http\Controllers\WhatsUpController::class)->group(function (){
  route::get('/sendwhats','SendWhats')->name('sendwhats');
});

