<?php

namespace Livewire\Volt;

use AllowDynamicProperties;
use Illuminate\Support\Facades\View;
use Livewire\Component as LivewireComponent;
use Livewire\Mechanisms\ComponentRegistry;
use Livewire\Volt\Actions\ReturnViewData;

#[AllowDynamicProperties]
abstract class Component extends LivewireComponent
{
    /**
     * The locally cached component alias for this command.
     */
    protected ?string $__alias = null;

    /**
     * Render the component.
     */
    final public function render(): mixed
    {
        $alias = $this->getAlias();

        $data = property_exists($this, '__context')
            ? (new ReturnViewData)->execute(static::$__context, $this, []) // @phpstan-ignore-line
            : [];

        return ($fragment = ExtractedFragment::fromAlias($alias))
            ? View::file($fragment->extractIfStale()->path(), $data)
            : View::make('volt-livewire::'.$alias, $data);
    }

    /**
     * Get the name of the component that should be rendered.
     */
    public function voltComponentName(): string
    {
        $alias = $this->getAlias();

        return FragmentAlias::decode($alias)['name'] ?? $alias;
    }

    /**
     * Get the alias of the component that should be rendered.
     */
    public function getAlias(): string
    {
        return $this->__alias ??= array_search(static::class, (fn () => $this->aliases)->call(
            app(ComponentRegistry::class),
        ));
    }
}
