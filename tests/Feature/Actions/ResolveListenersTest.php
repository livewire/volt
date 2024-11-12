<?php

use Livewire\Volt\Actions\ResolveListeners;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\InlineListenerName;

use function Livewire\Volt\on;

it('resolves the registered listeners', function () {
    $context = CompileContext::instance();

    on(fn () => ['first' => 'foo']);
    on(['second' => 'bar']);
    on(['third' => 'baz']);

    $component = new class extends Component {};

    $result = (new ResolveListeners)->execute($context, $component, []);

    expect($result)->toBe(['first' => 'foo', 'second' => 'bar', 'third' => 'baz']);
});

it('resolves the deferred listeners', function () {
    $context = CompileContext::instance();

    on(fn () => [
        'someEvent'.$this->testId => 'foo',
    ]);

    on(fn () => [
        'someEvent'.($this->testId + 1) => 'foo',
    ]);

    $component = new class extends Component
    {
        public $testId = 1;
    };

    $result = (new ResolveListeners)->execute($context, $component, []);

    expect($result)->toBe(['someEvent1' => 'foo', 'someEvent2' => 'foo']);
});

it('can retrieve component data when resolving event names', function () {
    $context = CompileContext::instance();

    on([$eventName = 'echo:foo.{firstId}.bar.{secondId}' => fn () => true]);

    $component = new class extends Component
    {
        public $firstId = 1;

        public $secondId = 2;
    };

    $result = (new ResolveListeners)->execute($context, $component, []);

    expect(InlineListenerName::for($eventName))->toBe('echofoofirstidbarsecondidHandler')
        ->and($result)->toBe([
            'echo:foo.1.bar.2' => 'echofoofirstidbarsecondidHandler',
        ]);
});
