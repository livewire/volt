<?php

use Livewire\Volt\Component;

new class extends Component
{
    public function action()
    {
        $this->missingActionOrHelper();
    }
} ?>

<div>
    <button wire:click="action"/>
</div>
