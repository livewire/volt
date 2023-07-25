<?php

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(
    color: '',
    name: fn (stdClass $request) => $request->name,
    counter: fn ($counter) => $counter,
    word: fn (stdClass $request, string $word, string $color) => $word,
);

mount(function (string $color) {
    $this->color = $color;
});

$increment = fn () => $this->counter++;

?>

<div>
    Color: {{ $color }}.
    Counter: {{ $counter }}.
    Name: {{ $name }}.
    Word: {{ $word }}.
    <br/>
    <button wire:click="increment">
        Increment Counter
    </button>
</div>
