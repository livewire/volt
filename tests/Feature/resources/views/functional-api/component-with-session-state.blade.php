<?php

use function Livewire\Volt\computed;
use function Livewire\Volt\state;

state(tab: 'livewire')->session();

$tags = computed(function () {
    return collect([
        'react' => [
            'React 19',
            'Typescript',
            'Inertia 2',
            'shadcdn/ui',
        ],
        'vue' => [
            'Vue 3',
            'Typescript',
            'Inertia 2',
            'shadcdn-vue',
        ],
        'livewire' => [
            'Livewire 3',
            'Laravel Volt',
            'Flux UI',
        ],
    ][$this->tab] ?? []);
});

?>

<div>
    <h1>Choose your Starter Kit:</h1>

    <input wire:model="tab" type="radio" id="react" name="tab" value="react">
    <label for="react">React</label>

    <input wire:model="tab" type="radio" id="vue" name="tab" value="vue">
    <label for="vue">Vue</label>

    <input wire:model="tab" type="radio" id="livewire" name="tab" value="livewire">
    <label for="livewire">Livewire</label>

    <ul>
        @foreach($this->tags as $tag)
            <li>{{ $tag }}</li>
        @endforeach
    </ul>
</div>
