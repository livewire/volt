<?php

namespace Livewire\Volt\Actions;

use Closure;
use Livewire\Exceptions\MissingRulesException;
use Livewire\Exceptions\PropertyNotFoundException;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;
use Livewire\Volt\Methods\Method;
use Livewire\Volt\Support\Reflection;

class CallMethod implements Action
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected string $methodName)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): mixed
    {
        $method = $context->variables["{$this->methodName}"];

        $method = $method instanceof Method ? $method->reflection()->closure : $method;

        $method = Closure::bind($method, $component, $component::class);

        try {
            return call_user_func_array($method, $arguments);
        } catch (PropertyNotFoundException $e) {
            $propertyName = explode(
                ' ', preg_replace('/Property \[\$(.*)\] not found on component:/', '$1', $e->getMessage())
            )[0];

            Reflection::setExceptionMessage(
                $e, "State definition for [$propertyName] not found on component [{$component->voltComponentName()}]."
            );

            throw $e;
        } catch (MissingRulesException $e) {
            Reflection::setExceptionMessage($e, "[rules()] declaration not found in [{$component->voltComponentName()}].");

            throw $e;
        }
    }
}
