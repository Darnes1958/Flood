<x-filament-panels::page>
    {{$this->form}}
    {{$this->table}}
    <x-filament::modal id="talentModal"  width="2xl" sticky-header>
        <x-slot name="heading">

        </x-slot>
        @livewire(\App\Filament\User\Pages\CreateTalent::class)        {{-- Modal content --}}
    </x-filament::modal>
</x-filament-panels::page>
