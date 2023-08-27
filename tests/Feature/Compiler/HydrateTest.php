<?php

use Illuminate\Session\SessionManager;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\hydrate;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('hydrate');
});

it('may be defined', function () {
    hydrate(fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function hydrate()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallHook('hydrate'))->execute(...$arguments);
        }
    PHP
    );
});

it('may be defined for a property', function () {
    hydrate(name: fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function hydrateProperty($name)
        {
            $arguments = [static::$__context, $this, array_slice(func_get_args(), 1)];

            return (new Actions\CallPropertyHook('hydrateProperty', $name))->execute(...$arguments);
        }

    PHP
    )->not->toContain('hydrate(');
});

test('dependency injection', function () {
    hydrate(fn (SessionManager $manager) => $manager->flush());

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function hydrate(\Illuminate\Session\SessionManager $manager)
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallHook('hydrate'))->execute(...$arguments);
        }
    PHP
    );
});
