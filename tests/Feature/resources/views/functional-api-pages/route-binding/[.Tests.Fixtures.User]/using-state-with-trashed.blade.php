<?php

use function Laravel\Folio\withTrashed;
use function Livewire\Volt\state;

withTrashed();

state('user');

?>

<div>
    <div>
        Folio {{ $user->id }} using state with trashed.
    </div>

    @volt
    <div>
        Volt {{ $user->id }} using state with trashed.
    </div>
    @endvolt
</div>
