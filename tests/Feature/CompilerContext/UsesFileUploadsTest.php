<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\usesFileUploads;
use Livewire\WithFileUploads;

it('is not used by default', function () {
    $context = CompileContext::instance();

    expect($context->traits)->not->toContain(WithFileUploads::class);
});

it('may be used', function () {
    $context = CompileContext::instance();

    usesFileUploads();

    expect($context->traits)->toContain(WithFileUploads::class);
});
