<?php

namespace Livewire\Volt\Compilers;

use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;
use Livewire\Volt\Contracts\FunctionalComponent;

class Interfaces implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        return collect($context->uses)
            ->prepend(FunctionalComponent::class)
            ->filter(fn (string $interface) => interface_exists($interface))
            ->values()->all();
    }
}
