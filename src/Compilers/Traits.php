<?php

namespace Livewire\Volt\Compilers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;

class Traits implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        return collect($context->uses)
            ->prepend(AuthorizesRequests::class)
            ->filter(fn (string $trait) => trait_exists($trait))
            ->map(fn (string $trait) => <<<PHP
                use {$trait};

            PHP)->values()->all();
    }
}
