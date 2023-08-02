<div>
    <span>Events received: {{ $counter }}.</span>
</div>

<?php

use function Livewire\Volt\on;
use function Livewire\Volt\state;

state(counter: 0);

on(eventName: fn () => $this->counter++);
