<?php

namespace Livewire\Volt;

use Livewire\Features\SupportAttributes\Attribute;

class Property
{
    /**
     * Create a property instance.
     *
     * @param  array<int, array{0: class-string<Attribute>, 1: array<string, mixed>}>  $attributes
     */
    protected function __construct(
        public mixed $value,
        public ?string $type = null,
        public array $attributes = [],
    ) {
    }

    /**
     * Create a property instance.
     */
    public static function make(mixed $value): static
    {
        return new static($value);
    }

    /**
     * Set the property's type.
     */
    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Determine if the property has the given attribute.
     *
     * @param  class-string<Attribute>  $name
     */
    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Add an attribute to the property.
     *
     * @param  class-string<Attribute>  $name
     * @param  array<string, mixed>  $arguments
     */
    public function attribute(string $name, mixed ...$arguments): static
    {
        $this->attributes[$name] = array_filter($arguments, fn (mixed $argument) => ! is_null($argument));

        return $this;
    }
}
