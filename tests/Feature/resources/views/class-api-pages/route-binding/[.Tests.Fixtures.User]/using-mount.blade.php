<?php

use Livewire\Volt\Component;
use Tests\Fixtures\User;

new class extends Component
{
    public User $user;
};

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
