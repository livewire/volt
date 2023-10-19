<?php

namespace Livewire\Volt;

use AllowDynamicProperties;
use BadMethodCallException;
use Illuminate\Container\Container;
use Illuminate\Support\Facades;
use Illuminate\Support\Str;
use Livewire\Component as LivewireComponent;
use Livewire\Mechanisms\ComponentRegistry;
use Livewire\Volt\Actions\ReturnLayout;
use Livewire\Volt\Actions\ReturnTitle;
use Livewire\Volt\Actions\ReturnViewData;
use Livewire\Volt\Contracts\FunctionalComponent;
use Livewire\Volt\Support\Reflection;

#[AllowDynamicProperties]
abstract class Component extends LivewireComponent
{
    /**
     * The locally cached component alias for this command.
     */
    protected ?string $__alias = null;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        Container::getInstance()
            ->make(ComponentFactory::class)
            ->setLatestCreatedComponentClass(static::class);
    }

    /**
     * Render the component.
     */
    final public function render(): mixed
    {
        $alias = $this->getAlias();

        $data = $this instanceof FunctionalComponent
            ? (new ReturnViewData)->execute(static::$__context, $this, []) // @phpstan-ignore-line
            : (method_exists($this, 'with') ? Container::getInstance()->call([$this, 'with']) : []);

        $layout = $this instanceof FunctionalComponent
            ? (new ReturnLayout)->execute(static::$__context, $this, []) // @phpstan-ignore-line
            : null;

        $title = $this instanceof FunctionalComponent
            ? (new ReturnTitle)->execute(static::$__context, $this, []) // @phpstan-ignore-line
            : null;

        $view = ($fragment = ExtractedFragment::fromAlias($alias))
            ? Facades\View::file($fragment->extractIfStale()->path(), $data)
            : Facades\View::make('volt-livewire::'.$alias, $data);

        if ($layout) {
            $view->layout($layout);
        }

        if ($title) {
            $view->title($title);
        }

        return $view;
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

    /**
     * {@inheritDoc}
     */
    public function __call($method, $params)
    {
        try {
            return parent::__call($method, $params);
        } catch (BadMethodCallException $e) {
            $message = $e->getMessage();

            if (str_starts_with($message, 'Method Livewire\Volt\Component@anonymous') &&
                str_ends_with($message, 'does not exist.')
            ) {
                $classAndMethodName = explode(' ', preg_replace('/Method (.*) does not exist./', '$1', $message))[0];

                $methodName = Str::afterLast($classAndMethodName, '::');

                Reflection::setExceptionMessage($e, "Method, action or protected callable [{$methodName}] not found on component [{$this->voltComponentName()}].");
            }

            throw $e;
        }
    }
}
