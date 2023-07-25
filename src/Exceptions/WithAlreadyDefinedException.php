<?php

namespace Livewire\Volt\Exceptions;

use InvalidArgumentException;

class WithAlreadyDefinedException extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     */
    public function __construct()
    {
        parent::__construct('The "with" function may only be invoked once per component.');
    }
}
