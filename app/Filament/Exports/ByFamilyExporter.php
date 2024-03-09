<?php

namespace App\Filament\Exports;

use App\Models\Victim;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

use OpenSpout\Common\Entity\Style\Style;

class ByFamilyExporter extends Exporter
{
    protected static ?string $model = Victim::class;

    public static function getColumns(): array
    {
        return [
          ExportColumn::make('FullName')
           ->state(function (Victim $record){
             if ($record->is_father)
               return 'الأب : '.$record->FullName.'  الابناء : ';
           })
          ->label('الاسم'),
          ExportColumn::make('father.Name1')

          ,


          ExportColumn::make('Street.StrName')
           ->label('العنوان'),
        ];
    }

  public function getXlsxCellStyle(): ?Style
  {
    return (new Style())
      ->setFontSize(12)
      ->setFontName('DejaVu Sans');
  }
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your by family export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
