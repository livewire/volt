<?php

use function Laravel\Folio\middleware;
use Livewire\Volt\Component;

middleware(fn () => abort(403, 'Unauthorized action from middleware'));

new class extends Component
{
    public function mount()
    {
        abort(403, 'Unauthorized action from mount');
    }
};
