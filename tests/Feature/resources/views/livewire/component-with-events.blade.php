<div>
    <span>Events received: {{ $counter }}.</span>
</div>

<?php

use function Livewire\Volt\{on, state};

state(counter: 0);

on(eventName: fn () => $this->counter++);
