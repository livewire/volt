<?php

namespace Livewire\Volt\Exceptions;

use InvalidArgumentException;

class ReturnNewClassExecutionEndingException extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     */
    public function __construct()
    {
        parent::__construct(
            'The [return new class extends Component { ... }] statement is not allowed in Volt components. Use [new class extends Component { ... }] instead.',
        );
    }
}
