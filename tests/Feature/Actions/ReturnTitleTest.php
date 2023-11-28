<?php

use Livewire\Volt\Actions\ReturnTitle;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('returns the title view', function () {
    $context = CompileContext::make();

    $context->title = 'my title';

    $component = new class extends Component
    {
    };

    $result = (new ReturnTitle)->execute($context, $component, []);

    expect($result)->toBe('my title');
});

it('returns a static title using a closure', function () {
    $context = CompileContext::make();

    $context->title = fn () => 'my title from a closure';

    $component = new class extends Component
    {
    };

    $result = (new ReturnTitle)->execute($context, $component, []);

    expect($result)->toBe('my title from a closure');
});

it('returns a computed title using a closure', function () {
    $context = CompileContext::make();

    $context->title = fn () => "welcome back $this->username";

    $component = new class extends Component
    {
        public string $username = 'Tom';
    };

    $result = (new ReturnTitle)->execute($context, $component, []);

    expect($result)->toBe('welcome back Tom');
});
