<?php

namespace Livewire\Volt\Methods;

use Closure;
use Livewire\Attributes\Js;

class JsMethod extends Method
{
    /**
     * Create a method instance.
     */
    public static function make(Closure|string $closureOrCode): static
    {
        $closure = $closureOrCode instanceof Closure
            ? $closureOrCode
            : fn () => $closureOrCode;

        return tap(new static($closure))->attribute(Js::class);
    }
}
