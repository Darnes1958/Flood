<?php

namespace App\Filament\Resources\VictimResource\Pages;

use App\Filament\Resources\VictimResource;
use App\Models\Victim;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateVictim extends CreateRecord
{
  protected ?string $heading = '';
    public function getBreadcrumbs(): array
    {
        return [""];
    }
    protected static string $resource = VictimResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
       if ($data['mother_id']) Victim::find($data['mother_id'])->update(['is_mother'=>1]);
       if ($data['father_id']) Victim::find($data['father_id'])->update(['is_father'=>1]);
       $data['FullName']=$data['Name1'].' '.$data['Name2'].' '.$data['Name3'].' '.$data['Name4'];
      return parent::mutateFormDataBeforeCreate($data); // TODO: Change the autogenerated stub
    }
    public function getTitle(): string|Htmlable
    {
      return 'إضافة ضحية جديدة'; // TODO: Change the autogenerated stub
    }

  protected function getRedirectUrl(): string
  {

    return $this->getResource()::getUrl('index');
  }
}