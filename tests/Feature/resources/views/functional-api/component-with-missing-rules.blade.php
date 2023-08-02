<?php

use function Livewire\Volt\{state};

$store = fn () => $this->validate();
?>

<div>
    <button wire:click="store" />
</div>
