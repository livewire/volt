<?php

use function Livewire\Volt\{state, rules};

state(['title' => '']);

rules(fn () => ['title' => ['required', 'min:5']])
    ->messages(['title' => 'The title field is missing.']);

$save = function () {
    $this->validate();

    $this->saved = true;
}; ?>

<div>
    <form wire:submit="save">
        <input type="text" wire:model="title">
        @error('title') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Save</button>
    </form>
</div>


