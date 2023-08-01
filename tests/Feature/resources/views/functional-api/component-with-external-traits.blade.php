<?php

use function Livewire\Volt\{uses};
use Tests\Fixtures\IncrementTrait;

uses(IncrementTrait::class);

?>

<div>
    Counter: {{ $counter }}
    <br/>
    <button wire:click="increment">
        Increment Counter
    </button>
</div>
