<?php

use function Livewire\Volt\state;

state([
    'title' => 'Example of ?> causing problems',
    'message' => 'PHP starts with <?php and ends with ?>',
]);

$update = fn () => $this->title = 'Updated text containing ?>';
?>

<div>
    <h1>{{ $title }}</h1>
    <p>{{ $message }}</p>
    <button wire:click="update">Update text</button>
</div>
