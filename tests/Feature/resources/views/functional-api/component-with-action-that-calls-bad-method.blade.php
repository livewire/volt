<?php

use function Livewire\Volt\{state};

$action = fn () => $this->missingActionOrHelper();

?>

<div>
    <button wire:click="action" />
</div>
