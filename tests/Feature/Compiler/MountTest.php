<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Session\SessionManager;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use Livewire\Volt\Exceptions\SignatureMismatchException;
use function Livewire\Volt\mount;
use function Livewire\Volt\state;

it('is always defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function mount()
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }
    PHP
    );
});

it('may be defined', function () {
    mount(function (): void {
        //
    });

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function mount(): void
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }
    PHP
    );
});

test('dependency injection', function () {
    mount(fn (SessionManager $manager) => $manager->flush());

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function mount(\Illuminate\Session\SessionManager $manager)
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});

test('dependency injection having state and mount with similar signatures', function () {
    mount(fn (User $user) => $user->getQualifiedUpdatedAtColumn());
    state(user: fn (User $user) => $user);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function mount(\Illuminate\Foundation\Auth\User $user)
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});

test('dependency injection having state and mount with different signatures', function () {
    mount(fn (SessionManager $manager) => $manager->flush());
    state(user: fn (User $user) => $user);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function mount(\Illuminate\Session\SessionManager $manager, \Illuminate\Foundation\Auth\User $user)
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
});

test('mount and state must have the same signature', function () {
    mount(fn ($user) => $user);
    state(user: fn (User $user) => $user);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain(<<<'PHP'
        public function mount(\Illuminate\Foundation\Auth\User $user, \Illuminate\Session\SessionManager $manager)
        {
            (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

            (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
        }

    PHP
    );
})->throws(SignatureMismatchException::class, 'Mount and state closures must have the same signature.');
