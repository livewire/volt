<?php

namespace Livewire\Volt;

use Livewire\Exceptions\MethodNotFoundException;
use Livewire\LivewireManager as BaseLivewireManager;
use Livewire\Volt\Actions\InitializeState;
use Livewire\Volt\Support\Reflection;

class LivewireManager extends BaseLivewireManager
{
    /**
     * {@inheritDoc}
     */
    public function mount($name, $params = [], $key = null)
    {
        $params = is_string($params) ? [] : $params;

        try {
            InitializeState::$currentMountParameters = array_keys($params);

            return parent::mount($name, $params, $key);
        } finally {
            InitializeState::$currentMountParameters = [];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update($snapshot, $diff, $calls)
    {
        try {
            return parent::update($snapshot, $diff, $calls);
        } catch (MethodNotFoundException $e) {
            $componentInstance = $this->current();

            if ($componentInstance instanceof Component) {
                $method = collect($calls)->first(fn (array $call) => str_contains($e->getMessage(), sprintf(
                    'Public method [%s]', $call['method'],
                )), fn () => throw $e)['method'];

                Reflection::setExceptionMessage($e, "Method or action [$method] does not exist on component [{$componentInstance->voltComponentName()}].");
            }

            throw $e;
        }
    }
}
