<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\rules;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('rules');
});

it('may be defined', function () {
    rules([
        'name' => 'required|min:3',
        'email' => 'required|email',
    ]);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        protected function rules()
        {
            return (new Actions\ReturnRules)->execute(static::$__context, $this, []);
        }
    PHP
    );
});
