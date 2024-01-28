<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\state;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('#[\Livewire\Attributes\Url()]');
});

it('may be defined', function () {
    state('searchOne')->url();
    state('searchTwo')->url(as: 'search', history: true);
    state('searchThree')->url(keep: true);
    state('searchFour')->url(keep: true, except: '');

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(
        <<<'PHP'
        #[\Livewire\Attributes\Url()]
        public $searchOne;

        #[\Livewire\Attributes\Url(as: 'search', history: true)]
        public $searchTwo;

        #[\Livewire\Attributes\Url(keep: true)]
        public $searchThree;

        #[\Livewire\Attributes\Url(keep: true, except: '')]
        public $searchFour;
    PHP
    );
});
