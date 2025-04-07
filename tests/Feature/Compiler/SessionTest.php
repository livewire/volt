<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\state;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('#[\Livewire\Attributes\Session()]');
});

it('may be defined', function () {
    state('tabOne')->session();
    state('tabTwo')->session(key: 'tab');

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(
        <<<'PHP'
        #[\Livewire\Attributes\Session()]
        public $tabOne;

        #[\Livewire\Attributes\Session(key: 'tab')]
        public $tabTwo;
    PHP
    );
});
