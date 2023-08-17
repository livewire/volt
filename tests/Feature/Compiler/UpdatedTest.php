<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\updated;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('updated');
});

it('may be defined', function () {
    updated(name: fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function updated($name)
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallPropertyHook('updated', $name))->execute(...$arguments);
        }

    PHP
    );
});
