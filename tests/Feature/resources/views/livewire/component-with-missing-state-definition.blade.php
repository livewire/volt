<?php

use function Livewire\Volt\{state};

$increment = fn () => $this->counter++;
?>

<div>
    <button wire:click="increment">
        Increment Counter
    </button>
</div>
