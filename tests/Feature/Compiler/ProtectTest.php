<?php

use Illuminate\Foundation\Auth\User;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use function Livewire\Volt\protect;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('protected function');
});

it('may be defined', function () {
    CompileContext::instance()->variables['myHelper'] = protect(fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        protected function myHelper()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('myHelper'))->execute(...$arguments);
        }
    PHP
    );
});

test('may be defined with complex signatures', function () {
    CompileContext::instance()->variables['myHelper'] = protect(fn (User $user): User => $user);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        protected function myHelper(\Illuminate\Foundation\Auth\User $user): \Illuminate\Foundation\Auth\User
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('myHelper'))->execute(...$arguments);
        }
    PHP
    );
});
