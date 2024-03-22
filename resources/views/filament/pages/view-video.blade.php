<x-filament::page>
    <video  controls>
        <source src="{{ route('getVideo', $record->id)  }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</x-filament::page>