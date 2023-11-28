<?php

namespace Livewire\Volt\Actions;

use Closure;
use Illuminate\Container\Container;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class ReturnTitle implements Action
{
    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): ?string
    {
        if ($context->title instanceof Closure) {
            return Container::getInstance()->call(
                Closure::bind($context->title, $component, $component::class),
            );
        }

        return $context->title;
    }
}
