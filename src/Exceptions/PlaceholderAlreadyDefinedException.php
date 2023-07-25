<?php

namespace Livewire\Volt\Exceptions;

use InvalidArgumentException;

class PlaceholderAlreadyDefinedException extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     */
    public function __construct()
    {
        parent::__construct('The "placeholder" function may only be invoked once per component.');
    }
}
