<?php

namespace Livewire\Volt\Methods;

use Closure;
use Livewire\Attributes\Computed;

class ComputedMethod extends Method
{
    /**
     * Create a method instance.
     */
    public static function make(Closure $closure): static
    {
        return tap(new static($closure))->attribute(Computed::class);
    }

    /**
     * Specify that the method should be cached.
     */
    public function persist(int $seconds = 3600): static
    {
        return $this->attribute(Computed::class, persist: true, seconds: $seconds);
    }
}
