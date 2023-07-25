<div>
    <form wire:submit="save">
        <input type="text" wire:model="form.title">
        @error('form.title') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Save</button>
    </form>
</div>

<?php

use Tests\Fixtures\BookForm;
use function Livewire\Volt\{form};

form(BookForm::class);

$save = function () {
    $this->form->validate();

    $this->saved = true;
};

?>
