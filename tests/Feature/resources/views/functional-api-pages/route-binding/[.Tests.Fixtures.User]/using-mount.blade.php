<?php

use function Livewire\Volt\mount;
use function Livewire\Volt\state;
use Tests\Fixtures\User;

state('user');

mount(fn (User $user) => $this->user = $user);

?>

<div>
    <div>
        Folio {{ $user->id }} using mount.
    </div>

    @volt
    <div>
        Volt {{ $user->id }} using mount.
    </div>
    @endvolt
</div>
