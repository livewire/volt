<?php

use function Livewire\Volt\boot;
use function Livewire\Volt\computed;
use function Livewire\Volt\state;

state('search', '')->url();

if (! class_exists(PostRepositoryWithDynamicProperties::class)) {
    class PostRepositoryWithDynamicProperties
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
}

boot(function (PostRepositoryWithDynamicProperties $posts) {
    $this->postsRepository = $posts;
});

$posts = computed(fn () => $this->postsRepository->search($this->search));

?>

<div>
    <input wire:model="search" type="search" placeholder="Search posts by name...">

    <h1>Search Results: </h1>

    <ul>
        @foreach($this->posts as $post)
            <li>{{ $post['name'] }}</li>
        @endforeach
    </ul>
</div>
