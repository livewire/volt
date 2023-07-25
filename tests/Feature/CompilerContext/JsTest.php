<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\js;
use Livewire\Volt\Methods\Method;
use Livewire\Attributes\Js;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->variables)->toBe([]);
});

it('may be defined', function () {
    $context = CompileContext::instance();

    $context->variables = [
        'reset' => js(fn () => <<<'JS'
            this.query = '';
        JS),
        'hello' => js('alert("Hello World!")'),
    ];

    expect($context->variables)->toContainOnlyInstancesOf(Method::class)
        ->and($context->variables['reset']->reflection()->attributes)->toBe([
            Js::class => [],
        ])->and($context->variables['hello']->reflection()->attributes)->toBe([
            Js::class => [],
        ]);
});
