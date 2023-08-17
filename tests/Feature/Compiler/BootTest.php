<?php

use Illuminate\Session\SessionManager;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\boot;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('boot');
});

it('may be defined', function () {
    boot(fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function boot()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallHook('boot'))->execute(...$arguments);
        }
    PHP
    );
});

test('dependency injection', function () {
    boot(function (SessionManager $manager): bool {
        $manager->flush();
    });

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function boot(\Illuminate\Session\SessionManager $manager): bool
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallHook('boot'))->execute(...$arguments);
        }
    PHP
    );
});
