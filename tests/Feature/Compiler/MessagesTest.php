<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\rules;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('messages');
});

it('may be defined', function () {
    rules()->messages([
        'email.required' => 'The Email Address cannot be empty.',
        'email.email' => 'The Email Address format is not valid.',
    ]);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        protected function messages()
        {
            return (new Actions\ReturnValidationMessages)->execute(static::$__context, $this, []);
        }
    PHP
    );
});
