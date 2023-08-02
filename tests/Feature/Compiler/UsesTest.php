<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use Livewire\Volt\Compilers\Traits;
use Livewire\Volt\Exceptions\TraitOrInterfaceNotFound;
use function Livewire\Volt\uses;
use Livewire\WithFileUploads;
use Tests\Fixtures\IncrementInterface;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('WithFileUploads');
});

it('may be defined', function () {
    uses([WithFileUploads::class, IncrementInterface::class]);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)
        ->toContain('implements Livewire\Volt\Contracts\FunctionalComponent, Tests\Fixtures\IncrementInterface')
        ->toContain(<<<'PHP'
        use Livewire\WithFileUploads;
    PHP
        );
});

it('may not be defined if trait or interface does not exist', function () {
    $context = CompileContext::instance();

    uses('MissingTrait');

    (new Traits)->compile($context);
})->throws(TraitOrInterfaceNotFound::class, 'Trait or interface [MissingTrait] not found.');
