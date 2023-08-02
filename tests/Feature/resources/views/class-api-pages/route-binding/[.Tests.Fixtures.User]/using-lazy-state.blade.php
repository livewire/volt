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
        Folio {{ $user->id }} using lazy state.
    </div>

    @volt
    <div>
        Volt {{ $user->id }} using lazy state.
    </div>
    @endvolt
</div>
