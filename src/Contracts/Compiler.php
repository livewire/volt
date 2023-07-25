<?php

namespace Livewire\Volt\Contracts;

use Livewire\Volt\CompileContext;

interface Compiler
{
    /**
     * Compile the given context into valid PHP code.
     *
     * @return array<int, string>
     */
    public function compile(CompileContext $context): array;
}
