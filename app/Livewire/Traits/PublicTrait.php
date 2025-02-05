<?php
namespace App\Livewire\Traits;



use App\Enums\AccLevel;
use App\Models\Rent;
use App\Models\Renttran;
use App\Models\Salary;
use App\Models\Salarytran;

use Carbon\Carbon;
use DateTime;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait PublicTrait {


  public static function ret_spatie_header(){
      return       $headers = [
          'Content-Type' => 'application/pdf',
      ];

  }
  public static function ret_spatie($res,$blade,$arr=[])
  {
      \Spatie\LaravelPdf\Facades\Pdf::view($blade,
          ['res'=>$res,'arr'=>$arr])
          ->save(public_path().'/invoice-2023-04-10.pdf');
      return public_path().'/invoice-2023-04-10.pdf';

  }
    public static function ret_spatie_land($res,$blade,$arr=[])
    {
        \Spatie\LaravelPdf\Facades\Pdf::view($blade,
            ['res'=>$res,'arr'=>$arr])
            ->landscape()
            ->save(public_path().'/invoice-2023-04-10.pdf');
        return public_path().'/invoice-2023-04-10.pdf';

    }




}
