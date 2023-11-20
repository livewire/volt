<?php

namespace Livewire\Volt\Actions;

use Closure;
use Illuminate\Container\Container;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class ReturnRules implements Action
{
    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): array
    {
        if ($context->rules instanceof Closure) {
            return Container::getInstance()->call(
                Closure::bind($context->rules, $component, $component::class),
            );
        }

        return $context->rules;
    }
}
