<?php

namespace Livewire\Volt\Methods;

use Closure;

class ReflectionMethod
{
    /**
     * Creates a new reflection method instance.
     */
    public function __construct(
        public Closure $closure,
        public string $visibility,
        public array $attributes,
    ) {}

    /**
     * Determine if the method is public.
     */
    public function isPublic(): bool
    {
        return $this->visibility === 'public';
    }

    /**
     * Determine if the method is protected.
     */
    public function isProtected(): bool
    {
        return $this->visibility === 'protected';
    }
}
