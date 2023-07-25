<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use function Livewire\Volt\state;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('#[\Livewire\Attributes\\Livewire\Attributes\Reactive]');
});

it('may be defined', function () {
    state('name')->reactive();
    state('email')->locked()->reactive();

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        #[\Livewire\Attributes\Reactive()]
        public $name;

        #[\Livewire\Attributes\Locked()]
        #[\Livewire\Attributes\Reactive()]
        public $email;
    PHP
    );
});
