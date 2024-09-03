<?php

namespace Livewire\Volt\Actions;

use Closure;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class CallListener implements Action
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected string $eventName) {}

    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): mixed
    {
        $handler = $context->inlineListeners[$this->eventName] ?? fn () => null;

        return call_user_func_array(Closure::bind($handler, $component, $component::class), $arguments);
    }
}
