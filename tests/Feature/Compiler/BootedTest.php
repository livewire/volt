<?php

use Illuminate\Session\SessionManager;
use function Livewire\Volt\booted;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('booted');
});

it('may be defined', function () {
    booted(fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function booted()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallHook('booted'))->execute(...$arguments);
        }
    PHP
    );
});

test('dependency injection', function () {
    booted(fn (SessionManager $manager) => $manager->flush());

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function booted(\Illuminate\Session\SessionManager $manager)
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallHook('booted'))->execute(...$arguments);
        }
    PHP
    );
});
