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

use function Livewire\Volt\{state};
use function Laravel\Folio\{middleware};
use Illuminate\Support\Str;

state('number', Str::upper(1));

middleware(function ($request, $next) {
    return $next($request);
});

?>
