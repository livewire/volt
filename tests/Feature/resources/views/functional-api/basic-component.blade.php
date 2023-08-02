<?php

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(name: '', counter: 0);

mount(fn ($name = null) => $this->name = $name ?? 'World');

$increment = fn () => $this->counter++;

?>

<div>
    Hello {{ $name }}
    <br/>
    Counter: {{ $counter }}
    <br/>
    <button wire:click="increment">
        Increment Counter
    </button>
</div>
