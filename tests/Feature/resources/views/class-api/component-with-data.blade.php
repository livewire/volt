<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $counter = 0;

    public function increment()
    {
        $this->counter++;
    }

    public function with(PlusOne $plusOne)
    {
        return [
            'counterPlusZero' => $this->counter + $plusOne->__invoke(2),
            'counterPlusOne' => $this->counter + 1 + $plusOne->__invoke(2),
            'counterCollection' => collect(range(0, $this->counter + $plusOne->__invoke(2))),
        ];
    }
} ?>

<div>
    <ul>
        <li>Counter Plus Zero: {{ $counterPlusZero }}.</li>
        <li>Counter Plus One: {{ $counterPlusOne }}.</li>
        <li>Counter Collection: {{ $counterCollection->implode(', ') }}.</li>
    </ul>
</div>
