<div>
    @php
        $number = Str::upper(2);
    @endphp

    <span>Folio {{ $number }} page definition after component.</span>

    @volt
    <div>
        Volt {{ $number }} page definition after component.
    </div>
    @endvolt
</div>

<?php

use Illuminate\Support\Str;
use function Laravel\Folio\{middleware};
use function Livewire\Volt\{state};

state('number', Str::upper(1));

middleware(function ($request, $next) {
    return $next($request);
});

?>
