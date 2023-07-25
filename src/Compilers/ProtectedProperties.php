<?php

namespace Livewire\Volt\Compilers;

use Illuminate\Support\Collection;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;

class ProtectedProperties implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        return collect()
            ->merge($this->compilePaginationTheme($context))
            ->toArray();
    }

    /**
     * Compile the pagination theme property based on what's defined on the context.
     */
    protected function compilePaginationTheme(CompileContext $context): Collection
    {
        if ($context->paginationTheme === null) {
            return collect();
        }

        $theme = var_export($context->paginationTheme, true);

        return collect(<<<PHP
            protected \$paginationTheme = $theme;
        PHP,
        );
    }
}
