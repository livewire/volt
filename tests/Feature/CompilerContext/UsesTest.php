<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\uses;
use Livewire\WithFileUploads;

it('is not used by default', function () {
    $context = CompileContext::instance();

    expect($context->traits)->not->toContain(WithFileUploads::class);
});

it('may be used', function () {
    $context = CompileContext::instance();

    uses(WithFileUploads::class);

    expect($context->traits)->toContain(WithFileUploads::class);
});
