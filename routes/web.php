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
  route::get('/pdffamily/{family_id}', 'PdfFamily')->name('pdffamily') ;
  Route::get('get-video/{video}', 'getVideo')->name('getVideo');
});

