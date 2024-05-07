<div class="flex justify-between">
    <div class="mx-1">
        @livewire(\App\Livewire\PlaceTypeWidget::class, ["record" => $record])
    </div>
    <div class="mx-1">
        @livewire(\App\Livewire\HareaWidget::class, ["record" => $record])
    </div>
    <div class="mx-1">
        @livewire(\App\Livewire\WakeelWidget::class, ["record" => $record])
    </div>
</div>
