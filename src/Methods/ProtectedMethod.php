<?php

namespace Livewire\Volt\Methods;

use Closure;

class ProtectedMethod extends Method
{
    /**
     * Create a method instance.
     */
    public static function make(Closure $closure): static
    {
        return new static($closure, 'protected');
    }
}
