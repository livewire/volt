<div>
    <form wire:submit="save">
        <input type="text" wire:model="form.title">
        @error('form.title') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Save</button>
    </form>
</div>

<?php

use function Livewire\Volt\{form};
use Tests\Fixtures\BookForm;

form(BookForm::class);

$save = function () {
    $this->form->validate();

    $this->saved = true;
};

?>
