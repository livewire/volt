<?php

use function Livewire\Volt\{action, state};

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
