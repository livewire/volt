<?php

use Illuminate\Session\SessionManager;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\dehydrate;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('dehydrate');
});

it('may be defined', function () {
    dehydrate(fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function dehydrate()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallHook('dehydrate'))->execute(...$arguments);
        }
    PHP
    );
});

it('may be defined for a property', function () {
    dehydrate(name: fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function dehydrateProperty($name)
        {
            $arguments = [static::$__context, $this, array_slice(func_get_args(), 1)];

            return (new Actions\CallPropertyHook('dehydrateProperty', $name))->execute(...$arguments);
        }

    PHP
    )->not->toContain('dehydrate(');
});

test('dependency injection', function () {
    dehydrate(fn (SessionManager $manager) => $manager->flush());

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function dehydrate(\Illuminate\Session\SessionManager $manager)
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallHook('dehydrate'))->execute(...$arguments);
        }
    PHP
    );
});
