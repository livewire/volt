<?php

namespace Livewire\Volt\Actions;

use Closure;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class CallPropertyHook implements Action
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected string $hookName, protected string $propertyName)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): mixed
    {
        $hook = $context->{$this->hookName}[$this->propertyName] ?? fn () => null;

        return call_user_func_array(
            Closure::bind($hook, $component, $component::class), $arguments
        );
    }
}
