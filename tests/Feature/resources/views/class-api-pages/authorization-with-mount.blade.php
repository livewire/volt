<?php

use Livewire\Volt\Component;
use function Laravel\Folio\middleware;

middleware(fn() => abort(403, 'Unauthorized action from middleware'));

new class extends Component
{
    public function mount()
    {
        abort(403, 'Unauthorized action from mount');
    }
};
