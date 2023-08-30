<?php

use function Laravel\Folio\{render};
use Livewire\Volt\Component;

render(fn () => abort(403, 'Unauthorized action from middleware'));

new class extends Component
{
    public string $content = 'Hello world';
} ?>

<div>
    @volt
        {{ $content }}
    @endvolt
</div>
