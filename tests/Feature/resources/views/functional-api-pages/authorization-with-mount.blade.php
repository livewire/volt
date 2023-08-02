<?php

use function Laravel\Folio\middleware;
use function Livewire\Volt\{mount};

mount(fn () => abort(403, 'Unauthorized action from mount'));

middleware(fn () => abort(403, 'Unauthorized action from middleware'));
