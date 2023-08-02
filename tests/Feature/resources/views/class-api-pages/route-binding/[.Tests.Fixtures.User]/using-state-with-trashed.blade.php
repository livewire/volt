<?php

use function Laravel\Folio\withTrashed;
use Livewire\Volt\Component;

withTrashed();

new class extends Component
{
    public $user;
};

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
