<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('public $');
});

it('may be defined', function () {
    state(name: 'Nuno');
    state(['email' => 'nuno@laravel.com']);
    state('address', 'Portugal');

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public $name;

        public $email;

        public $address;

        public function mount()
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});

it('may be defined with multiple properties', function () {
    state(['name' => 'Nuno', 'email' => 'nuno@laravel.com', 'user' => fn () => auth()->user()]);
    state('address', 'Portugal');

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public $name;

        public $email;

        public $user;

        public $address;

        public function mount()
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});

test('dependency injection', function () {
    state(name: fn (Request $request) => $request->user()->name);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public $name;

        public function mount(\Illuminate\Http\Request $request)
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});

test('dependency injection with key as first argument and value as second', function () {
    state('name', fn (Request $request) => $request->user()->name);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public $name;

        public function mount(\Illuminate\Http\Request $request)
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});

test('dependency injection with props', function () {
    state(name: fn (Request $request, User $user) => $user->name);

    mount(fn (Request $request, User $user) => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public $name;

        public function mount(\Illuminate\Http\Request $request, \Illuminate\Foundation\Auth\User $user)
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});

test('dependency injection with props using key as first argument and value as second', function () {
    state('name', fn (Request $request, User $user) => $user->name);

    mount(fn (Request $request, User $user) => null);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public $name;

        public function mount(\Illuminate\Http\Request $request, \Illuminate\Foundation\Auth\User $user)
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});
