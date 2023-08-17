<?php

use Livewire\Attributes\Renderless;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Methods\ActionMethod;

use function Livewire\Volt\action;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->variables)->toBe([]);
});

it('may be defined', function () {
    $context = CompileContext::instance();

    $context->variables = [
        'actionVariable' => action(fn () => 1),
        'actionVariableRenderless' => action(fn () => 1)->renderless(),
    ];

    expect($context->variables)->toContainOnlyInstancesOf(ActionMethod::class)
        ->and($context->variables['actionVariable']->reflection()->attributes)->toBe([])
        ->and($context->variables['actionVariableRenderless']->reflection()->attributes)->toBe([
            Renderless::class => [],
        ]);
});
