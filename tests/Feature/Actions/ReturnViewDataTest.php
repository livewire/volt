<?php

use Livewire\Volt\Actions\ReturnViewData;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('may return view data lazily', function () {
    $context = CompileContext::make();

    $context->viewData = fn (MyClass $class) => [
        'a' => 'a',
        'b' => $this->b,
        'c' => $class->c,
    ];

    $component = new class extends Component
    {
        public string $b = 'b';
    };

    $result = (new ReturnViewData)->execute($context, $component, []);

    expect($result)->toBe([
        'a' => 'a',
        'b' => 'b',
        'c' => 'c',
    ]);
});

it('may return view data from array', function () {
    $context = CompileContext::make();

    $context->viewData = fn () => [
        'a' => 'a',
        'b' => fn () => $this->b,
        'c' => fn (MyClass $class) => $class->c,
    ];

    $component = new class extends Component
    {
        public string $b = 'b';
    };

    $result = (new ReturnViewData)->execute($context, $component, []);

    expect($result)->toBe([
        'a' => 'a',
        'b' => 'b',
        'c' => 'c',
    ]);
});

class MyClass
{
    public string $c = 'c';
}
