<?php

namespace Livewire\Volt\Actions;

use Closure;
use Illuminate\Container\Container;
use Livewire\Attributes\Url;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class InitializeState implements Action
{
    /**
     * The parameters the component was mounted with.
     *
     * @var array<int, string>
     */
    public static array $currentMountParameters = [];

    /**
     * {@inheritDoc}
     */
    public function execute(CompileContext $context, Component $component, array $arguments): void
    {
        $properties = array_diff_key(
            $context->state,
            array_flip(static::$currentMountParameters)
        );

        foreach ($properties as $key => $property) {
            if ($property->hasAttribute(Url::class) && ! is_null($component->{$key})) {
                continue;
            }

            $value = $property->value;

            if ($value instanceof Closure) {
                $value = Container::getInstance()->call(
                    Closure::bind($value, $component, $component::class), $arguments
                );

                $component->{$key} = $value;
            } elseif (! is_null($value)) {
                $component->{$key} = $value;
            }
        }
    }
}
