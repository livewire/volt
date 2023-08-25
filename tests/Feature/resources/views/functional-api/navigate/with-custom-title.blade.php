<?php

use function Livewire\Volt\{state, title};

state('content', 'content with custom title');

title('custom title');

?>

<div>
    <h1>Content: {{ $content }}.</h1>
</div>
