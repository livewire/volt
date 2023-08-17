<?php

use Livewire\Volt\CompileContext;
use Livewire\WithFileUploads;
use Tests\Fixtures\IncrementInterface;

use function Livewire\Volt\uses;

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
