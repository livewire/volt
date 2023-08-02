<?php
use function Livewire\Volt\{state, uses};
use Tests\Fixtures\IncrementInterface;
uses([IncrementInterface::class, \Tests\Fixtures\IncrementTrait::class]);
$alsoIncrement = function (): void {
    $this->counter++;
};
$alsoIncrementButReturnInt = fn (): int => $this->counter++;
?>

<div>
    Counter: {{ $counter }}
    <br/>
    <button wire:click="increment">
        Increment Counter
    </button>
    <button wire:click="alsoIncrement">
        Increment Counter
    </button>
    <button wire:click="alsoIncrementButReturnInt">
        Increment Counter
    </button>
</div>
