<?php

use Livewire\Volt\Component;

use function Livewire\Volt\{state};
use function Laravel\Folio\{render};

render(fn ($view) => $view->with('name', 'from view'));

state('name', 'from component'); ?>

<div>
    @volt('fragment-component')
    <div>
        <span>Hello {{ $name }}.</span>
    </div>
    @endvolt
</div>
