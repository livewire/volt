<?php

namespace Livewire\Volt\Actions;

use Closure;
use Illuminate\Container\Container;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Contracts\Action;

class ReturnViewData implements Action
{
    /**
     * {@inheritDoc}
     *
     * @return array<string, mixed>
     */
    public function execute(CompileContext $context, Component $component, array $_): array
    {
        if ($context->viewData === null) {
            return [];
        }

        $viewData = $this->call($component, $context->viewData);

        return array_map(
            fn (mixed $value) => $value instanceof Closure ? $this->call($component, $value) : $value,
            $viewData,
        );
    }

    /**
     * Call the given callback using the container.
     */
    protected function call(Component $component, Closure $callback): mixed
    {
        return Container::getInstance()->call(
            Closure::bind($callback, $component, $component::class)
        );
    }
}
