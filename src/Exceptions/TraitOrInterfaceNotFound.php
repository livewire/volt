<?php

namespace Livewire\Volt\Exceptions;

use InvalidArgumentException;

class TraitOrInterfaceNotFound extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $name)
    {
        parent::__construct("Trait or interface [$name] not found.");
    }
}
