<?php

use function Livewire\Volt\{state};

state(['name' => 'World']);

?>

<div>
    @volt('fragment-component')
    <div>
        Hello {{ $name }}
    </div>
    @endvolt
</div>
