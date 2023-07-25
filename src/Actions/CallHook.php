<?php

namespace Livewire\Volt\Actions;

use Closure;
use Illuminate\Container\Container;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class CallHook implements Action
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected string $hookName)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): mixed
    {
        $hook = $context->{$this->hookName};

        if ($this->hookName === 'mount') {
            return Container::getInstance()->call(
                Closure::bind($hook, $component, $component::class), $arguments
            );
        }

        return call_user_func_array(Closure::bind($hook, $component, $component::class), $arguments);
    }
}
