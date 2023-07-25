<?php

use function Laravel\Folio\middleware;

middleware(fn () => abort(401, 'Unauthorized action from middleware'));
