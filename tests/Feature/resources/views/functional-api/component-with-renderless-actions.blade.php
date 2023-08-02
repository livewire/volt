<?php

use function Livewire\Volt\action;
use function Livewire\Volt\state;

state('counter', 0);

$increment = action(fn () => $this->counter++)->renderless(); ?>

<div>
    <br/>
    Counter: {{ $counter }}.
    <br/>
    <button wire:click="increment">
        Increment Counter
    </button>
</div>
