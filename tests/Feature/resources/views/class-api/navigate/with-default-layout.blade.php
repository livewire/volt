<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $content = 'content with default layout';
}; ?>

?>

<div>
    <h1>Content: {{ $content }}.</h1>
</div>
