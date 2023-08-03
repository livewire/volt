<?php

namespace Livewire\Volt\Exceptions;

use RuntimeException;

class VoltDirectiveMissingException extends RuntimeException
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $bladePath)
    {
        parent::__construct(
            'The [@volt] directive is required when using Volt anonymous components in Folio pages. The directive is missing in ['.$bladePath.'].',
        );
    }
}
