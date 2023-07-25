<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use function Livewire\Volt\rules;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('validationAttributes');
});

it('may be defined', function () {
    rules()->attributes([
        'name' => 'full name',
        'email' => 'email address',
    ]);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        protected function validationAttributes()
        {
            return (new Actions\ReturnValidationAttributes)->execute(static::$__context, $this, []);
        }
    PHP
    );
});
