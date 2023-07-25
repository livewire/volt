<?php

namespace Livewire\Volt\Contracts;

use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

interface Action
{
    /**
     * Execute the action.
     *
     * @param  array<string, mixed>  $arguments
     */
    public function execute(CompileContext $context, Component $component, array $arguments);
}
