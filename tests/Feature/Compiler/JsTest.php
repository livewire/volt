<?php

use Illuminate\Foundation\Auth\User;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\js;

it('may be defined', function () {
    CompileContext::instance()->variables = [
        'reset' => js(fn () => <<<'JS'
            this.query = '';
        JS),
        'hello' => js('alert("Hello World!")'),
    ];

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        #[\Livewire\Attributes\Js()]
        public function reset()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('reset'))->execute(...$arguments);
        }

        #[\Livewire\Attributes\Js()]
        public function hello()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('hello'))->execute(...$arguments);
        }
    PHP
    );
});

test('may be defined with complex signatures', function () {
    CompileContext::instance()->variables['reset'] = js(fn (User $user): string => <<<'JS'
        this.query = '';
    JS);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        #[\Livewire\Attributes\Js()]
        public function reset(\Illuminate\Foundation\Auth\User $user): string
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('reset'))->execute(...$arguments);
        }
    PHP
    );
});
