<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $name = 'World';
}

?>

<div>
    @volt('fragment-component')
    <div>
        Hello {{ $name }}
    </div>
    @endvolt
</div>
