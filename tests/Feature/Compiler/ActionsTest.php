<?php

use Illuminate\Foundation\Auth\User;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use Livewire\Volt\Methods\ActionMethod;

it('may be defined', function () {
    CompileContext::instance()->variables['myAction'] = ActionMethod::make(function () {
        return 'Hello World';
    });

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function myAction()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('myAction'))->execute(...$arguments);
        }
    PHP
    );
});

test('may be defined with complex signatures', function () {
    CompileContext::instance()->variables['myAction'] = ActionMethod::make(function (User $user): User {
        return $user;
    });

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function myAction(\Illuminate\Foundation\Auth\User $user): \Illuminate\Foundation\Auth\User
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('myAction'))->execute(...$arguments);
        }
    PHP
    );
});
