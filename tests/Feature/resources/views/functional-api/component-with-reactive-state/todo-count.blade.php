<div>
    Count: {{ count($todos) }}.
</div>

<?php

use function Livewire\Volt\state;

state('todos')->reactive();
