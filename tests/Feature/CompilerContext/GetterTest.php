<?php

use Livewire\Attributes\Computed;
use Livewire\Volt\CompileContext;
use function Livewire\Volt\computed;
use Livewire\Volt\Methods\Method;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->variables)->toBe([]);
});

it('may be defined', function () {
    $context = CompileContext::instance();

    $context->variables = [
        'computedVariable' => computed(fn () => 1),
        'computedVariableWithPersist' => computed(fn () => 1)->persist(),
        'computedVariableWithPersistAndSeconds' => computed(fn () => 1)->persist(seconds: 30),
    ];

    expect($context->variables)->toContainOnlyInstancesOf(Method::class)
        ->and($context->variables['computedVariable']->reflection()->attributes)->toBe([
            Computed::class => [],
        ])->and($context->variables['computedVariableWithPersist']->reflection()->attributes)->toBe([
            Computed::class => ['persist' => true, 'seconds' => 3600],
        ])->and($context->variables['computedVariableWithPersistAndSeconds']->reflection()->attributes)->toBe([
            Computed::class => ['persist' => true, 'seconds' => 30],
        ]);
});
