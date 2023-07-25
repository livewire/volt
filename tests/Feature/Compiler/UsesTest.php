<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use Livewire\Volt\Compilers\Traits;
use Livewire\Volt\Exceptions\TraitNotFound;
use function Livewire\Volt\uses;
use Livewire\WithFileUploads;

it('may not be defined', function () {
    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->not->toContain('WithFileUploads');
});

it('may be defined', function () {
    uses(WithFileUploads::class);

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        use Livewire\WithFileUploads;
    PHP
    );
});

it('may not be defined if trait does not exist', function () {
    $context = CompileContext::instance();

    uses('MissingTrait');

    (new Traits)->compile($context);
})->throws(TraitNotFound::class, 'Trait [MissingTrait] not found.');
