<?php

namespace Livewire\Volt\Exceptions;

use InvalidArgumentException;

class TraitNotFound extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $name)
    {
        parent::__construct("Trait [$name] not found.");
    }
}
