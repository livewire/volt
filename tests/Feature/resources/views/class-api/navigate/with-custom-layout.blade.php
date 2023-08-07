<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.custom')] class extends Component
{
    public $content = 'content with custom layout';
}; ?>

?>

<div>
    <x-slot name="title">
        custom title
    </x-slot>

    <h1>Content: {{ $content }}.</h1>
</div>
