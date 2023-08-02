<?php

use Livewire\Volt\Component;

new class extends Component
{
    public function store()
    {
        $this->validate();
    }
} ?>

<div>
    <button wire:click="store" />
</div>
