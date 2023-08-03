<?php

use Illuminate\Support\Facades\Blade;
use Laravel\Folio\Folio;
use Livewire\Volt\Exceptions\VoltDirectiveMissingException;
use Livewire\Volt\Precompilers\ExtractFragments;

beforeEach(function () {
    $this->precompiler = new class(config('view.compiled')) extends ExtractFragments
    {
        public ?string $name = null;

        public ?array $arguments = null;

        protected function directive(string $name, array $arguments): string
        {
            $this->name = $name;
            $this->arguments = $arguments;

            return parent::directive($name, $arguments);
        }
    };

    Blade::shouldReceive('getPath')->once()->andReturn(__DIR__.'/my-component-path.blade.php');
});

test('eager', function () {
    [$name, $arguments] = precompileFragments(<<<'HTML'
        <div>
            @volt
                Hello World
            @endvolt
        </div>
        HTML);

    expect($name)->toStartWith('volt-anonymous-fragment-')
        ->and($arguments)->toBe([]);
});

test('eager and lazy', function () {
    [$name, $arguments] = precompileFragments(<<<'HTML'
        <div>
            @volt(lazy: true)
                Hello World
            @endvolt
        </div>
        HTML);

    expect($name)->toStartWith('volt-anonymous-fragment-')
        ->and($arguments)->toBe([
            'lazy' => true,
        ]);
});

test('eager and lazy on load', function () {
    [$name, $arguments] = precompileFragments(<<<'HTML'
        <div>
            @volt(['lazy' => true, 'on-load' => true])
                Hello World
            @endvolt
        </div>
        HTML);

    expect($name)->toStartWith('volt-anonymous-fragment-')
        ->and($arguments)->toBe([
            'lazy' => true,
            'on-load' => true,
        ]);
});

test('eager and lazy on load with extra arguments', function () {
    [$name, $arguments] = precompileFragments(<<<'HTML'
        <div>
            @volt(['lazy' => true, 'on-load' => true, 'my-argument' => 'my-value'])
                Hello World
            @endvolt
        </div>
        HTML);

    expect($name)->toStartWith('volt-anonymous-fragment-')
        ->and($arguments)->toBe([
            'lazy' => true,
            'on-load' => true,
            'my-argument' => 'my-value',
        ]);
});

test('named', function () {
    [$name, $arguments] = precompileFragments(<<<'HTML'
        <div>
            @volt('my-component')
                Hello World
            @endvolt
        </div>
        HTML);

    expect($name)->toStartWith('my-component')
        ->and($arguments)->toBe([]);
});

test('named and lazy', function () {
    [$name, $arguments] = precompileFragments(<<<'HTML'
        <div>
            @volt('my-component', lazy: true)
                Hello World
            @endvolt
        </div>
        HTML);

    expect($name)->toStartWith('my-component')
        ->and($arguments)->toBe([
            'lazy' => true,
        ]);
});

test('named and lazy on load', function () {
    [$name, $arguments] = precompileFragments(<<<'HTML'
        <div>
            @volt('my-component', ['lazy' => true, 'on-load' => true])
                Hello World
            @endvolt
        </div>
        HTML);

    expect($name)->toStartWith('my-component')
        ->and($arguments)->toBe([
            'lazy' => true,
            'on-load' => true,
        ]);
});

test('named and lazy on load with extra arguments', function () {
    [$name, $arguments] = precompileFragments(<<<'HTML'
        <div>
            @volt('my-component', ['lazy' => true, 'on-load' => true, 'my-argument' => 'my-value'])
                Hello World
            @endvolt
        </div>
        HTML);

    expect($name)->toStartWith('my-component')
        ->and($arguments)->toBe([
            'lazy' => true,
            'on-load' => true,
            'my-argument' => 'my-value',
        ]);
});

test('folio pages using volt anonymous components require the @volt directive', function (string $html) {
    Folio::route(__DIR__);

    precompileFragments($html);
})->throws(VoltDirectiveMissingException::class)->with([
    <<<'HTML'
        <?php use function Livewire\Volt\state;

        state(count: 0);

        ?>

        <div>{{ $count }}</div>
        HTML,
    <<<'HTML'
        <?php use Livewire\Volt\Component;

        new class extends Component {
            public $count = 0;
        };

        ?>

        <div>{{ $count }}</div>
        HTML,

    <<<'HTML'
        <?php use function Livewire\Volt\state;

        state(count: 0);

        ?>

        <div>{{ Str::upper($count) }}</div>
        HTML,
]);

function precompileFragments(string $template): array
{
    test()->precompiler->__invoke($template);

    return [test()->precompiler->name, test()->precompiler->arguments];
}
