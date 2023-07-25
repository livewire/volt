<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use Livewire\Volt\ComponentFactory;
use Livewire\Volt\ComponentResolver;
use Livewire\Volt\Volt;
use Livewire\Exceptions\ComponentNotFoundException;
use Livewire\Exceptions\MethodNotFoundException;
use Livewire\Exceptions\MissingRulesException;
use Livewire\Exceptions\PropertyNotFoundException;
use Livewire\Livewire;
use Livewire\Mechanisms\ComponentRegistry;
use Pest\TestSuite;
use Tests\Fixtures\GlobalTrait;

beforeEach(function () {
    Volt::mount([__DIR__.'/resources/views/pages', __DIR__.'/resources/views/livewire'], [GlobalTrait::class]);
});

it('can be rendered', function () {
    Livewire::test('basic-component')
        ->assertSee('Hello World');

    Volt::test('basic-component')
        ->assertSee('Hello World');
});

it('can render fragments', function () {
    Volt::test('fragment-component', ['name' => 'Taylor'])
        ->assertSee('Hello Taylor');
});

it('can render pages with multiple fragments', function () {
    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    Volt::test('second-fragment-component', ['name' => 'Taylor'])
        ->assertSee('Second - Hello Taylor');
});

it('can have the template first', function () {
    Livewire::test('component-with-template-first')
        ->assertSee('Hello World');

    Volt::test('component-with-template-first')
        ->assertSee('Hello World');
});

it('can have placeholder', function () {
    Livewire::test('component-with-placeholder.eager-dashboard')
        ->assertSee('Real feed');

    Livewire::test('component-with-placeholder.lazy-dashboard')
        ->assertSee('Placeholder of feed');
});

it('can be rendered with mount data', function () {
    Livewire::test('basic-component', ['name' => 'Taylor'])
        ->assertSee('Hello Taylor');
});

it('can call actions', function () {
    Livewire::test('basic-component', ['name' => 'Taylor'])
        ->assertSet('counter', 0)
        ->call('increment')
        ->call('increment')
        ->call('increment')
        ->assertSet('counter', 3);
});

it('can have renderless action', function () {
    Livewire::test('component-with-renderless-actions', ['counter' => 1])
        ->assertSet('counter', 1)
        ->assertSee('Counter: 1.')
        ->call('increment') // This action is renderless
        ->assertSee('Counter: 1.');
});

it('can have reusable methods', function () {
    Livewire::test('component-with-reusable-methods')
        ->assertSee('Counter: 2.')
        ->call('incrementAsMethod')
        ->assertSee('Counter: 3.')
        ->call('incrementAsCallable')
        ->assertSee('Counter: 4.');
});

it('can have parameters', function (array $parameters, array $state) {
    $component = Livewire::test('component-with-parameters', $parameters);

    collect($state)->each(fn ($value, $key) => $component->assertSet($key, $value));
})->with([
    [[], ['string' => 'initial-string', 'null' => null]],
    [['string' => 'overridden-string'], ['string' => 'overridden-string', 'null' => null]],
    [['string' => null], ['string' => null, 'null' => null]],
    [['null' => 'overridden-null'], ['string' => 'initial-string', 'null' => 'overridden-null']],
    [['string' => 'overridden-string', 'null' => 'overridden-null'], ['string' => 'overridden-string', 'null' => 'overridden-null']],
]);

it('can have locked state', function () {
    $component = Livewire::test('component-with-locked-state');

    $component->assertSet('name', 'initial-name');
    $component->assertSet('id', 'initial-id');

    $component->updateProperty('name', 'overridden-name');
    $component->updateProperty('id', 'overridden-id');
})->throws(Exception::class, 'Cannot update locked property: [id]');

it('can have computed properties', function () {
    $component = Livewire::test('component-with-computed');

    $component->assertSee('Computed value.');
});

it('can have view data using closure', function () {
    $component = Livewire::test('component-with-view-data.using-closure');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
        '<li>Taylor</li>',
    ]);

    $component->assertSee('Current search is: .');
    $component->assertSee('Some other data is: someOtherData.');

    $component->set('search', 'Nuno');

    $component->assertSee('Current search is: Nuno.');
    $component->assertSee('Some other data is: someOtherData.');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
    ]);

    $component->assertDontSeeHtml([
        '<li>Taylor</li>',

    ]);
});

it('can have view data using array with closures', function () {
    $component = Livewire::test('component-with-view-data.using-array');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
        '<li>Taylor</li>',
    ]);

    $component->assertSee('Current search is: .');
    $component->assertSee('Some other data is: someOtherData.');

    $component->set('search', 'Nuno');

    $component->assertSee('Current search is: Nuno.');
    $component->assertSee('Some other data is: someOtherData.');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
    ]);

    $component->assertDontSeeHtml([
        '<li>Taylor</li>',

    ]);
});

it('can have form properties', function () {
    $component = Livewire::test('component-with-form-state');

    $component->assertSet('saved', false)
        ->call('save')
        ->assertSee('The title field is required.')
        ->assertSet('saved', false)
        ->updateProperty('form.title', 'Hello')
        ->call('save')
        ->assertDontSee('The title field is required.')
        ->assertSet('saved', true);
});

it('can have dynamic properties', function () {
    $component = Livewire::test('component-with-dynamic-properties');

    $instance = $component->instance();

    expect($instance->postsRepository)->toBeObject()
        ->and($instance->all())->not->toHaveKey('postsRepository');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
        '<li>Taylor</li>',
    ]);

    $component->set('search', 'Nuno');

    $instance = $component->instance();

    expect($instance->postsRepository)->toBeObject()
        ->and($instance->all())->not->toHaveKey('postsRepository');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
    ]);

    $component->assertDontSeeHtml([
        '<li>Taylor</li>',

    ]);
});

it('can have reactive state', function () {
    $component = Livewire::test('component-with-reactive-state.todos');

    $component->assertSee('Count: 0.');

    $component->set('todos', ['todo 1', 'todo 2']);
    // $component->assertSee('Count: 2.');

    $component->set('todos', ['todo 1', 'todo 2', 'todo 3']);
    // $component->assertSee('Count: 3.');
});

it('can listen events', function () {
    $component = Livewire::test('component-with-events');

    $component->assertSee('Events received: 0.')
        ->dispatch('eventName')
        ->assertSee('Events received: 1.')
        ->dispatch('eventName')
        ->assertSee('Events received: 2.');
});

it('can have url state', function () {
    $component = Livewire::test('component-with-url-state');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
        '<li>Taylor</li>',
    ]);

    $component->set('search', 'Nuno');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
    ]);

    $component->assertDontSeeHtml([
        '<li>Taylor</li>',
    ]);
});

it('throws exception when "method" is not found', function () {
    LiveWire::component('vanilla-component', new class extends \Livewire\Component
    {
        public function render()
        {
            return '<div></div>';
        }
    });

    Livewire::test('vanilla-component')
        ->call('missing-method');
})->throws(
    MethodNotFoundException::class,
    'Unable to call component method. Public method [missing-method] not found on component',
);

it('throws exception when "action" is not found', function () {
    Livewire::test('basic-component', ['name' => 'Taylor'])
        ->call('missing-action');
})->throws(
    MethodNotFoundException::class,
    'Closure [missing-action] does not exist on component [basic-component].',
);

it('throws exception when rules are not found', function () {
    Livewire::test('component-with-missing-rules')
        ->call('store');
})->throws(
    MissingRulesException::class,
    '[rules()] declaration not found in [component-with-missing-rules].',
);

it('throws exception when method is not found within action', function () {
    Livewire::test('component-with-action-that-calls-bad-method', ['name' => 'Taylor'])
        ->call('action');
})->throws(
    BadMethodCallException::class,
    'Action or protected callable [missingActionOrHelper] not found on component [component-with-action-that-calls-bad-method].',
);

it('throws exception when state definition is not found', function () {
    Livewire::test('component-with-missing-state-definition', ['name' => 'Taylor'])
        ->call('increment');
})->throws(
    PropertyNotFoundException::class,
    'State definition for [counter] not found on component [component-with-missing-state-definition].',
);

it('reuses components compiled classes within the same request', function () {
    File::partialMock();

    $componentA = Livewire::test('basic-component', ['name' => 'Taylor']);
    $componentA->assertSee('Hello Taylor');
    $componentAClass = get_class($componentA->instance());

    $componentB = Livewire::test('basic-component', ['name' => 'Nuno']);
    $componentB->assertSee('Hello Nuno');
    $componentBClass = get_class($componentB->instance());

    expect($componentAClass)->toBe($componentBClass);

    File::shouldHaveReceived('put')
        ->once()
        ->withArgs(function (string $path, string $contents) {
            return str_contains($path, storage_path())
                && str_contains($contents, 'return new class extends Component');
        });
});

it('reuses components compiled classes in subsequent requests', function () {
    File::partialMock();

    $componentA = Livewire::test('basic-component', ['name' => 'Taylor']);
    $componentA->assertSee('Hello Taylor');
    $componentAClass = get_class($componentA->instance());

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    $componentB = Livewire::test('basic-component', ['name' => 'Nuno']);
    $componentB->assertSee('Hello Nuno');
    $componentBClass = get_class($componentB->instance());

    File::shouldHaveReceived('put')
        ->once()
        ->withArgs(function (string $path, string $contents) {
            return str_contains($path, storage_path())
                && str_contains($contents, 'return new class extends Component');
        });

    // In different requests, while the "compiled" class is the same, the "anonymous" class name is different...
    expect($componentAClass)->not->toBe($componentBClass);
});

it('does not reuse components compiled classes in subsequent requests if `view:clear` was used', function () {
    File::partialMock();

    $componentA = Livewire::test('basic-component', ['name' => 'Taylor']);
    $componentA->assertSee('Hello Taylor');
    $componentAClass = get_class($componentA->instance());

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    // Clear the views cache...
    Artisan::call('view:clear');

    $componentB = Livewire::test('basic-component', ['name' => 'Nuno']);
    $componentB->assertSee('Hello Nuno');
    $componentBClass = get_class($componentB->instance());

    File::shouldHaveReceived('put')
        ->times(2)
        ->withArgs(function (string $path, string $contents) {
            return str_contains($path, storage_path())
                && str_contains($contents, 'return new class extends Component');
        });

    expect($componentAClass)->not->toBe($componentBClass);
});

it('does not reuse components compiled classes when the component file changes', function () {
    $original = __DIR__.'/resources/views/livewire/basic-component.blade.php';

    File::partialMock();

    $componentA = Livewire::test('basic-component', ['name' => 'Taylor']);
    $componentA->assertSee('Hello Taylor');
    $componentAClass = get_class($componentA->instance());

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    touch($original); // Simulate a change to the component file...
    clearstatcache();

    $componentB = Livewire::test('basic-component', ['name' => 'Nuno']);
    $componentB->assertSee('Hello Nuno');
    $componentBClass = get_class($componentB->instance());

    File::shouldHaveReceived('put')
        ->twice()
        ->withArgs(function (string $path, string $contents) {
            return str_contains($path, storage_path())
                && str_contains($contents, 'return new class extends Component');
        });

    expect($componentAClass)->not->toBe($componentBClass);
});

it('caches components using their compiled view paths', function () {
    File::partialMock();

    Livewire::test('basic-component')
        ->assertSee('Hello World');

    File::shouldHaveReceived('put')
        ->once()
        ->withArgs(function (string $path) {
            return str_contains($path, storage_path());
        });
});

it('may use external traits', function () {
    Livewire::test('component-with-external-traits')
        ->assertSet('counter', 0)
        ->call('increment')
        ->call('increment')
        ->call('increment')
        ->assertSet('counter', 3);
});

test('generated code', function (string $filename) {

    (fn () => $this->requirePath(TestSuite::getInstance()->rootPath.$filename))
        ->call(app(ComponentFactory::class));

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toMatchSnapshot();
})->with(function () {
    $files = [];

    foreach (glob(__DIR__.'/resources/views/livewire/*.blade.php') as $file) {
        $files[basename($file)] = str_replace(
            TestSuite::getInstance()->rootPath,
            '',
            $file,
        );
    }

    return $files;
});

test('dependency injection', function () {
    $this->swap(stdClass::class, (object) [
        'name' => 'Nuno',
    ]);

    Livewire::test('component-dependency-injection', ['color' => 'blue', 'word' => 'hello', 'counter' => 0])
        ->assertSee('Color: blue.')
        ->assertSee('Counter: 0.')
        ->assertSee('Name: Nuno.')
        ->assertSee('Word: hello.')
        ->call('increment')
        ->assertSee('Color: blue.')
        ->assertSee('Counter: 1.')
        ->assertSee('Name: Nuno.')
        ->assertSee('Word: hello.');

    Livewire::test('component-dependency-injection', ['counter' => 0, 'word' => 'hello', 'color' => 'blue'])
        ->assertSee('Color: blue.')
        ->assertSee('Counter: 0.')
        ->assertSee('Name: Nuno.')
        ->assertSee('Word: hello.')
        ->call('increment')
        ->assertSee('Color: blue.')
        ->assertSee('Counter: 1.')
        ->assertSee('Name: Nuno.')
        ->assertSee('Word: hello.');
});

it('can apply traits globally', function () {
    $value = Livewire::test('basic-component')
        ->assertSee('Hello World')
        ->get('globalProperty');

    expect($value)->toBeNull();

    $value = Livewire::test('basic-component')
        ->assertSee('Hello World')
        ->call('setGlobalProperty', 'foo')
        ->get('globalProperty');

    expect($value)->toBe('foo');
});

it('may use unqualified names', function () {
    Livewire::test('component-with-unqualified-names')
        ->assertSee('Hello World');
});

it('may use multiple scripts', function () {
    Livewire::test('component-with-multiple-scripts', [
        'first' => 'Taylor',
    ])->assertSee('Hello Taylor')->assertSee('Hello Otwell');
});

it('throws component not found exception when component does not exist', function () {
    Livewire::test('non-existent-component', ['name' => 'Taylor'])->dd()
        ->assertSee('Second - Hello Taylor');
})->throws(
    ComponentNotFoundException::class,
    'Unable to find component: [non-existent-component]'
);

it('reuses components within the same request', function () {
    $resolver = Mockery::mock($this->app->get(ComponentResolver::class))->makePartial();

    $this->swap(ComponentResolver::class, $resolver);

    $componentA = Livewire::test('basic-component', ['name' => 'Taylor']);
    $componentA->assertSee('Hello Taylor');
    $componentAClass = get_class($componentA->instance());

    $componentB = Livewire::test('basic-component', ['name' => 'Nuno']);
    $componentB->assertSee('Hello Nuno');
    $componentBClass = get_class($componentB->instance());

    expect($componentAClass)->toBe($componentBClass);

    $resolver->shouldHaveReceived('resolve')
        ->once()
        ->with('basic-component', [
            __DIR__.'/resources/views/pages',
            __DIR__.'/resources/views/livewire',
        ]);
});

it('reuses fragments within the same request', function () {
    Volt::test('basic-component')
        ->assertSee('Hello World');

    File::partialMock();

    view()->file(__DIR__.'/resources/views/pages/page-with-multiple-fragments.blade.php')->render();

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    view()->file(__DIR__.'/resources/views/pages/page-with-multiple-fragments.blade.php')->render();

    Volt::test('first-fragment-component', ['name' => 'Nuno'])
        ->assertSee('First - Hello Nuno');

    view()->file(__DIR__.'/resources/views/pages/page-with-multiple-fragments.blade.php')->render();

    Volt::test('first-fragment-component', ['name' => 'Nuno'])
        ->assertSee('First - Hello Nuno');

    view()->file(__DIR__.'/resources/views/pages/page-with-multiple-fragments.blade.php')->render();

    Volt::test('first-fragment-component', ['name' => 'Nuno'])
        ->assertSee('First - Hello Nuno');

    File::shouldHaveReceived('put')
        ->times(2)
        ->withArgs(function (string $path, string $contents) {
            return str($path)->contains(storage_path())
                && str($contents)->contains([
                    '- Hello',
                    'new class extends Component',
                ]);
        });
});

it('reuses cached fragment components in subsequent requests', function () {
    Volt::test('basic-component')
        ->assertSee('Hello World');

    File::partialMock();

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    view()->file(__DIR__.'/resources/views/pages/page-with-multiple-fragments.blade.php')->render();

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    view()->file(__DIR__.'/resources/views/pages/page-with-multiple-fragments.blade.php')->render();

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    File::shouldHaveReceived('put')
        ->times(2)
        ->withArgs(function (string $path, string $contents) {
            return str($path)->contains(storage_path())
                && str($contents)->contains([
                    '- Hello',
                    'new class extends Component',
                ]);
        });
});

it('reuses fragment components in subsequent requests', function () {
    Volt::test('basic-component')
        ->assertSee('Hello World');

    File::partialMock();

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    File::shouldHaveReceived('put')
        ->times(1)
        ->withArgs(function (string $path, string $contents) {
            if (str($path)->contains(storage_path())
                && str($contents)->contains([
                    '- Hello',
                    'new class extends Component',
                ])) {
                return true;
            }
        });
});

it('does not reuse component components in subsequent requests if `view:clear` was used', function () {
    Volt::test('basic-component')
        ->assertSee('Hello World');

    File::partialMock();

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));
    Artisan::call('view:clear');

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    File::shouldHaveReceived('put')
        ->times(3)
        ->withArgs(function (string $path, string $contents) {
            return str($path)->contains(storage_path())
                && str($contents)->contains([
                    '- Hello',
                    'new class extends Component',
                ]);
        });

    (fn () => $this->aliases = [])->call(app(ComponentRegistry::class));
    Artisan::call('view:clear');

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    File::shouldHaveReceived('put')
        ->times(6)
        ->withArgs(function (string $path, string $contents) {
            return str($path)->contains(storage_path())
                && str($contents)->contains([
                    '- Hello',
                    'new class extends Component',
                ]);
        });
});

it('does not reuse component template if original view changed', function () {
    Volt::test('basic-component')
        ->assertSee('Hello World');

    File::partialMock();

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    File::shouldHaveReceived('put')
        ->times(1)
        ->withArgs(function (string $path, string $contents) {
            return str($path)->contains(storage_path())
                && str($contents)->contains([
                    '- Hello',
                    'new class extends Component',
                ]);
        });

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    File::shouldHaveReceived('put')
        ->times(1)
        ->withArgs(function (string $path, string $contents) {
            return str($path)->contains(storage_path())
                && str($contents)->contains([
                    '- Hello',
                    'new class extends Component',
                ]);
        });

    touch(__DIR__.'/resources/views/pages/page-with-multiple-fragments.blade.php');

    Volt::test('first-fragment-component', ['name' => 'Taylor'])
        ->assertSee('First - Hello Taylor');

    File::shouldHaveReceived('put')
        ->times(3)
        ->withArgs(function (string $path, string $contents) {
            return str($path)->contains(storage_path())
                && str($contents)->contains([
                    '- Hello',
                    'new class extends Component',
                ]);
        });
});
