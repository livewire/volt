<?php

use Livewire\Volt\Component;

return new class extends Component
{
    public $name = 'World';
    public $counter = 0;

    public function increment()
    {
        $this->counter++;
    }
}; ?>

<div>
    Hello {{ $name }}
    <br/>
    Counter: {{ $counter }}
    <br/>
    <button wire:click="increment">
        Increment Counter
    </button>
</div>
