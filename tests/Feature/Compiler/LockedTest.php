<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use function Livewire\Volt\state;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('#[\Livewire\Attributes\Locked()]');
});

it('may be defined', function () {
    state('id')->locked();

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        #[\Livewire\Attributes\Locked()]
        public $id;
    PHP
    );
});
