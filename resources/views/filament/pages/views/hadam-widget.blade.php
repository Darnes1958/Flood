<div class="flex justify-between">
    <div>
        @livewire(\App\Livewire\PlaceTypeWidget::class, ["record" => $record])
    </div>
    <div>
        @livewire(\App\Livewire\hareaWidget::class, ["record" => $record])
    </div>
</div>
