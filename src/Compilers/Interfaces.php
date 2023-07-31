<?php

namespace Livewire\Volt\Compilers;

use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;

class Interfaces implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        return collect($context->uses)
            ->filter(fn (string $interface) => interface_exists($interface))
            ->values()->all();
    }
}
