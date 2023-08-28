<div>
    Hello {{ $name }}
    <br/>
    Counter: {{ $counter }}
    <br/>
    <button wire:click="increment">
        Increment Counter
    </button>
</div>

<?php

use Tests\Fixtures\User;
use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(name: '', counter: 0);

mount(fn ($name = null) => $this->name = $name ?? User::first()->name);

$increment = fn () => $this->counter++;
