<div class="flex">
    <p class="text-danger-600">{{$record->FullName}}</p>
    @if($record->husband)
        <p class="text-primary-400"> وزوجته </p>
        <p class="text-blue">{{$record->husband->FullName}}</p>
    @endif
    @if($record->wife)
        <p class="text-primary-400"> وزوجها </p>
      <p class="dark:text-danger-500">{{$record->wife->FullName}}</p>
    @endif
</div>
