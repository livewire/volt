<?php

use function Livewire\Volt\{mount};
use function Laravel\Folio\middleware;

mount(fn () => abort(403, 'Unauthorized action from mount'));

middleware(fn () => abort(403, 'Unauthorized action from middleware'));
