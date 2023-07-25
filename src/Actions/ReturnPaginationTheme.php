<?php

namespace Livewire\Volt\Actions;

use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class ReturnPaginationTheme implements Action
{
    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): ?string
    {
        return $context->paginationTheme;
    }
}
