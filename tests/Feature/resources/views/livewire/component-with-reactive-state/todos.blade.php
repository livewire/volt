<?php

use function Livewire\Volt\state;

state(['todos' => []]);

?>

<div>
    <livewire:component-with-reactive-state.todo-count :$todos />
</div>
