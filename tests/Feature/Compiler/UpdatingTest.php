<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\updating;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('updating');
});

it('may be defined', function () {
    updating(name: fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function updating($name)
        {
            $arguments = [static::$__context, $this, array_slice(func_get_args(), 1)];

            return (new Actions\CallPropertyHook('updating', $name))->execute(...$arguments);
        }

    PHP
    );
});
