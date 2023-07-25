<?php

namespace Livewire\Volt\Actions;

use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class ReturnValidationMessages implements Action
{
    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): array
    {
        return $context->messages;
    }
}
