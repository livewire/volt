<?php

namespace Livewire\Volt\Compilers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;
use Livewire\Volt\Exceptions\TraitNotFound;

class Traits implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        return collect($context->traits)
            ->prepend(AuthorizesRequests::class)
            ->each(fn (string $trait) => trait_exists($trait) || throw new TraitNotFound($trait))
            ->map(fn (string $trait) => <<<PHP
                use {$trait};

            PHP)->values()->all();
    }
}
