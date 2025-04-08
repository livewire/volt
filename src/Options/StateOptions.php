<?php

namespace Livewire\Volt\Options;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Volt\Property;

class StateOptions
{
    /**
     * @param  array<int, Property>  $properties
     */
    public function __construct(
        protected array $properties,
    ) {
        //
    }

    /**
     * Add an attribute to the properties.
     */
    public function attribute(string $name, mixed ...$arguments): static
    {
        foreach ($this->properties as $property) {
            $property->attribute($name, ...$arguments);
        }

        return $this;
    }

    /**
     * Set the type for the state's properties.
     */
    public function type(string $type): static
    {
        foreach ($this->properties as $property) {
            $property->type($type);
        }

        return $this;
    }

    /**
     * Set the state's properties as "locked".
     */
    public function locked(): static
    {
        return $this->attribute(Locked::class);
    }

    /**
     * Set the state's properties as "modelable".
     */
    public function modelable(): static
    {
        return $this->attribute(Modelable::class);
    }

    /**
     * Set the state's properties as "reactive".
     */
    public function reactive(): static
    {
        return $this->attribute(Reactive::class);
    }

    /**
     * Indicate the state should be tracked in the session.
     */
    public function session(?string $key = null): static
    {
        return $this->attribute(Session::class, key: $key);
    }

    /**
     * Indicate the state should be tracked in the URL.
     */
    public function url(?string $as = null, ?bool $history = null, ?bool $keep = null, ?string $except = null): static
    {
        return $this->attribute(Url::class, as: $as, history: $history, keep: $keep, except: $except);
    }
}
