<?php

use function Laravel\Folio\{render};
use function Livewire\Volt\{state};

render(fn () => abort(403, 'Unauthorized action from middleware'));

state('content', 'Hello world'); ?>

<div>
    @volt
        {{ $content }}
    @endvolt
</div>
