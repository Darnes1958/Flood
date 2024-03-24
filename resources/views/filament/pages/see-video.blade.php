<div>
    <p>
        @if($record->id)
            @php info($record->attachment);
                $embed = \Cohensive\OEmbed\Facades\OEmbed::get($record->attachment);
                if ($embed)
                  echo $embed->html();
             @endphp

        @endif

    </p>
</div>