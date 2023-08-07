<?php

use function Livewire\Volt\{layout, state};

state('content', 'content with default layout');

?>

<div>
    <h1>Content: {{ $content }}.</h1>
</div>
