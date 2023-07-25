<?php

use Illuminate\Foundation\Auth\User;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use function Livewire\Volt\computed;

it('may be defined', function () {
    CompileContext::instance()->variables['myComputed'] = computed(fn () => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        #[\Livewire\Attributes\Computed()]
        public function myComputed()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('myComputed'))->execute(...$arguments);
        }
    PHP
    );
});

test('may be defined with complex signatures', function () {
    CompileContext::instance()->variables['myComputed'] = computed(function (User $user): User {
        return $user;
    });

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        #[\Livewire\Attributes\Computed()]
        public function myComputed(\Illuminate\Foundation\Auth\User $user): \Illuminate\Foundation\Auth\User
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('myComputed'))->execute(...$arguments);
        }
    PHP
    );
});

it('may be defined with persist', function () {
    CompileContext::instance()->variables['myComputed'] = computed(fn () => null)->persist();

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        #[\Livewire\Attributes\Computed(persist: true, seconds: 3600)]
        public function myComputed()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('myComputed'))->execute(...$arguments);
        }
    PHP
    );
});

it('may be defined with persist and certain amount of seconds', function () {
    CompileContext::instance()->variables['myComputed'] = computed(fn () => null)->persist(seconds: 10);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        #[\Livewire\Attributes\Computed(persist: true, seconds: 10)]
        public function myComputed()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallMethod('myComputed'))->execute(...$arguments);
        }
    PHP
    );
});
