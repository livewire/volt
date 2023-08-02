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

use function Laravel\Folio\{middleware};
use Livewire\Volt\Component;
use function Livewire\Volt\{state};

middleware(function ($request, $next) {
    return $next($request);
});

new class extends Component
{
    public $number = '1';
}

?>
