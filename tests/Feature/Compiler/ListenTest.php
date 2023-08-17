<?php

use Illuminate\Session\SessionManager;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;

use function Livewire\Volt\on;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('listeners');
});

it('may be defined', function () {
    on(['userCreated' => fn () => null, 'userDeleted' => 'onUserDeleted']);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function getListeners()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\ResolveListeners)->execute(...$arguments);
        }

        public function usercreatedHandler()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallListener('userCreated'))->execute(...$arguments);
        }
    PHP
    );
});

test('dependency injection', function () {
    on(['userCreated' => fn (SessionManager $session) => $session, 'userDeleted' => 'onUserDeleted']);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public function getListeners()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\ResolveListeners)->execute(...$arguments);
        }

        public function usercreatedHandler(\Illuminate\Session\SessionManager $session)
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\CallListener('userCreated'))->execute(...$arguments);
        }
    PHP
    );
});
