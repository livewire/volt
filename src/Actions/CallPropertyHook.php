<?php

namespace Livewire\Volt\Actions;

use Closure;
use Illuminate\Support\Arr;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class CallPropertyHook implements Action
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected string $hookName, protected string $propertyName) {}

    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): mixed
    {
        if (! isset($context->{$this->hookName}[$this->propertyName]) && str_contains($this->propertyName, '.')) {
            $hookedProperties = array_keys($context->{$this->hookName});

            $matchingProperty = Arr::first($hookedProperties, fn ($key) => str($this->propertyName)->is("$key*"));

            if ($matchingProperty) {
                $additionalArgument = str($this->propertyName)->after($matchingProperty)->trim('.')->value();

                $this->propertyName = $matchingProperty;

                $arguments = array_merge([$additionalArgument], $arguments);
            }
        }

        $hook = $context->{$this->hookName}[$this->propertyName] ?? fn () => null;

        return call_user_func_array(
            Closure::bind($hook, $component, $component::class), $arguments
        );
    }
}
