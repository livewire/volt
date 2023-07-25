<?php

namespace Livewire\Volt\Methods;

use Closure;
use Livewire\Attributes\Renderless;

class ActionMethod extends Method
{
    /**
     * Create a method instance.
     */
    public static function make(Closure $closure): static
    {
        return new static($closure);
    }

    /**
     * Make the method renderless.
     */
    public function renderless(): static
    {
        return $this->attribute(Renderless::class);
    }
}
