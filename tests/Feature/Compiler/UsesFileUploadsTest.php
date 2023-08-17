<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\usesFileUploads;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('WithFileUploads');
});

it('may be defined', function () {
    usesFileUploads();

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        use Livewire\WithFileUploads;
    PHP
    );
});
