<?php

use function Livewire\Volt\state;
use Tests\Fixtures\User;

state('user', fn (User $user) => $user);

?>

<div>
    <div>
        Folio {{ $user->id }} using lazy state.
    </div>

    @volt
    <div>
        Volt {{ $user->id }} using lazy state.
    </div>
    @endvolt
</div>
