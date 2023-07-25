<?php

namespace Livewire\Volt;

class MountedDirectory
{
    /**
     * Create a new mounted directory instance.
     *
     * @param  array<int, class-string>  $uses
     * @return void
     */
    public function __construct(
        public string $path,
        public array $uses = [],
    ) {
    }
}
