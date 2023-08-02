<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $name = 'World';
} ?>

<div>
    @volt('first-fragment-component')
    <div>
        First - Hello {{ $name }}
    </div>
    @endvolt

    <div>
    </div>

    @volt('second-fragment-component')
    <div>
        Second - Hello {{ $name }}
    </div>
    @endvolt
</div>
