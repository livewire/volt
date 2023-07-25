<?php

use function Livewire\Volt\{state, with};

state('search', '')->url();

class PostRepositoryUsingClosure
{
    public function search(string $search): array
    {
        return collect([
            ['name' => 'Nuno'],
            ['name' => 'Nuno Maduro'],
            ['name' => 'Taylor'],
        ])->filter(fn (array $post) => str_contains($post['name'], $search))->toArray();
    }
}

with(function (PostRepositoryUsingClosure $posts) {
    return [
        'posts' => $posts->search($this->search),
        'currentSearch' => $this->search,
        'someOtherData' => 'someOtherData',
    ];
});

?>

<div>
    <input wire:model="search" type="search" placeholder="Search posts by name...">

    <h1>Search Results:</h1>

    <ul>
        @foreach($posts as $post)
            <li>{{ $post['name'] }}</li>
        @endforeach
    </ul>

    <h1>Current search is: {{ $currentSearch }}.</h1>
    <h1>Some other data is: {{ $someOtherData }}.</h1>
</div>

