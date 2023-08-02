<?php

use function Livewire\Volt\state;

state('user');

?>

<div>
    <div>
        Folio {{ $user->id }} using state.
    </div>

    @volt
    <div>
        Volt {{ $user->id }} using state.
    </div>
    @endvolt
</div>
