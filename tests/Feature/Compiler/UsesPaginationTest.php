<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use function Livewire\Volt\usesPagination;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('WithPagination');
});

it('may be defined with no options', function () {
    usesPagination();

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        use Livewire\WithPagination;
    PHP)
        ->not->toContain('paginationView')
        ->not->toContain('paginationTheme');
});

it('may be defined with an custom view', function () {
    usesPagination(view: 'my-custom-view');

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        use Livewire\WithPagination;
    PHP
    )->toContain(<<<'PHP'
        public function paginationView()
        {
            $arguments = [static::$__context, $this, func_get_args()];

            return (new Actions\ReturnPaginationView())->execute(...$arguments);
        }

    PHP)->not->toContain('paginationTheme');
});

it('may be defined with an custom theme', function () {
    usesPagination(theme: 'bootstrap');

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        use Livewire\WithPagination;
    PHP)->toContain(<<<'PHP'
            protected $paginationTheme = 'bootstrap';

        PHP
    )->not->toContain('paginationView');
});
