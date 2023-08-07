<?php

use function Livewire\Volt\{layout, state, with};

state('content', 'content with custom layout');

layout('components.layouts.custom');

?>

<div>
    <x-slot name="title">
        custom title
    </x-slot>

    <h1>Content: {{ $content }}.</h1>
</div>
