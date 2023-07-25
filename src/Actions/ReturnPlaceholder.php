<?php

namespace Livewire\Volt\Actions;

use Closure;
use Illuminate\Container\Container;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class ReturnPlaceholder implements Action
{
    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $_): mixed
    {
        if ($context->placeholder === null) {
            return [];
        }

        return Container::getInstance()->call(
            Closure::bind($context->placeholder, $component, $component::class)
        );
    }
}
