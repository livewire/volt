<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\placeholder;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('placeholder');
});

it('may be defined', function () {
    placeholder(fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function placeholder()
        {
            return (new Actions\ReturnPlaceholder())->execute(static::$__context, $this, []);
        }
    PHP
    );
});
