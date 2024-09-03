<?php

namespace Livewire\Volt\Methods;

use Closure;
use Livewire\Features\SupportAttributes\Attribute;
use Livewire\Livewire;

abstract class Method
{
    /**
     * Create a method instance.
     *
     * @param  array<int, array{0: class-string<Attribute>, 1: array<string, mixed>}>  $attributes
     */
    protected function __construct(
        protected Closure $closure,
        protected string $visibility = 'public',
        protected array $attributes = [],
    ) {}

    /**
     * Add an attribute to the method.
     */
    public function attribute(string $name, mixed ...$arguments): static
    {
        $this->attributes[$name] = array_filter($arguments, fn (mixed $argument) => ! is_null($argument));

        return $this;
    }

    /**
     * Get the method's reflection.
     *
     * @internal
     */
    public function reflection(): ReflectionMethod
    {
        return new ReflectionMethod($this->closure, $this->visibility, $this->attributes);
    }

    /**
     * Invoke the method's closure.
     */
    public function __invoke(...$arguments): mixed
    {
        $component = Livewire::current();

        return $this->closure->bindTo($component, $component::class)->__invoke(...$arguments);
    }
}
