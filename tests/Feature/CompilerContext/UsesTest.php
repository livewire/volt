<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\uses;
use Livewire\WithFileUploads;
use Tests\Fixtures\IncrementInterface;

it('is not used by default', function () {
    $context = CompileContext::instance();

    expect($context->uses)->not->toContain(WithFileUploads::class);
});

it('may be used', function () {
    $context = CompileContext::instance();

    uses(WithFileUploads::class);
    uses(IncrementInterface::class);

    expect($context->uses)->toContain(WithFileUploads::class)
        ->toContain(IncrementInterface::class);
});
