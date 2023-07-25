<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use function Livewire\Volt\on;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->inlineListeners)->toBe([])
        ->and($context->listeners)->toBeNull();
});

it('may have listeners using associative arrays', function () {
    $context = CompileContext::instance();

    on(['nameUpdated' => 'onNameUpdated', 'emailUpdated' => fn () => 'onEmailUpdated']);

    expect($context->inlineListeners['emailUpdated'])->resolve()->toBe('onEmailUpdated')
        ->and($context->listeners)->resolve(new class extends Component
        {
        })->toBe([
            'emailUpdated' => 'emailupdatedHandler',
            'nameUpdated' => 'onNameUpdated',
        ]);
});

it('may have listeners using named arguments', function () {
    $context = CompileContext::instance();

    on(nameUpdated: 'onNameUpdated', emailUpdated: fn () => 'onEmailUpdated');

    expect($context->inlineListeners['emailUpdated'])->resolve()->toBe('onEmailUpdated')
        ->and($context->listeners)->resolve(new class extends Component
        {
        })->toBe([
            'emailUpdated' => 'emailupdatedHandler',
            'nameUpdated' => 'onNameUpdated',
        ]);
});

it('may have listeners using a closure', function () {
    $context = CompileContext::instance();

    on(fn () => ['nameUpdated' => 'onNameUpdated', 'emailUpdated' => fn () => 'onEmailUpdated']);

    expect($context->inlineListeners)->toBe([])->and($context->listeners)->resolve(new class extends Component
    {
    })->sequence(
        fn ($closure, $name) => $closure->toBe('onNameUpdated') && $name->toBe('nameUpdated'),
        fn ($closure, $name) => $closure->resolve()->toBe('onEmailUpdated') && $name->toBe('emailUpdated'),
    );
});

test('may have listeners using a closure that is bound to the component', function () {
    $context = CompileContext::instance();

    $component = new class extends Component
    {
        public $testId = 1;
    };

    on(function () {
        return [
            'nameUpdated'.$this->testId => 'onNameUpdated1',
            'nameUpdated'.$this->testId + 1 => 'onNameUpdated2',
        ];
    });

    expect($context->listeners)->resolve($component)->sequence(
        fn ($closure, $name) => $closure->toBe('onNameUpdated1') && $name->toBe('nameUpdated1'),
        fn ($closure, $name) => $closure->toBe('onNameUpdated2') && $name->toBe('nameUpdated2'),
    );
});

it('precedence', function () {
    $context = CompileContext::instance();

    on(name: fn () => 'first', email: fn () => 'first');
    on(name: fn () => 'second');

    expect($context->inlineListeners['name'])->resolve()->toBe('second')
        ->and($context->inlineListeners['email'])->resolve()->toBe('first')
        ->and($context->listeners)->resolve(new class() extends Component
        {
        })->toBe([
            'name' => 'nameHandler',
            'email' => 'emailHandler',
        ]);
});
