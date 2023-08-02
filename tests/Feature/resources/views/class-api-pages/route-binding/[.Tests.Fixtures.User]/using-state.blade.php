<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $user;
};

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
