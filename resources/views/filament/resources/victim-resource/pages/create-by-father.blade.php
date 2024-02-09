<x-filament-panels::page>
    <div class="flex gap-2">

        <div class="w-8/12 gap-2 ">
            <div class="w-full">
                {{$this->familyForm}}
            </div>

            <div class="w-full pt-2">
                {{$this->victimForm}}
            </div>
        </div>
        <div class="w-4/12 ">
            {{$this->table}}
        </div>

    </div>
</x-filament-panels::page>
