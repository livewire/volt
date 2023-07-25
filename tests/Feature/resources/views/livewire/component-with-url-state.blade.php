<?php

use function Livewire\Volt\{computed, state};

state('search')->url();

$posts = computed(function () {
    return collect([
        ['name' => 'Nuno'],
        ['name' => 'Nuno Maduro'],
        ['name' => 'Taylor'],
    ])->filter(fn ($post) => str_contains($post['name'], $this->search));
});

?>

<div>
    <input wire:model="search" type="search" placeholder="Search posts by name...">

    <h1>Search Results:</h1>

    <ul>
        @foreach($this->posts as $post)
            <li>{{ $post['name'] }}</li>
        @endforeach
    </ul>
</div>
