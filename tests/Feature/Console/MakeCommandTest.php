<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    File::partialMock();
});

it('makes components', function (string $name, string $viewPath, string $testPath) {
    $this->artisan('make:volt', ['name' => $name])->assertOk();

    $viewPath = resource_path('views/livewire/'.$viewPath);
    $testPath = base_path('tests/Feature/Livewire'.$testPath);

    expect($viewPath)->toBeFile()
        ->and($testPath)->not->toBeFile()
        ->and(file_get_contents($viewPath))->toBe(
            <<<'PHP'
            <?php

            use function Livewire\Volt\{state};

            //

            ?>

            <div>
                //
            </div>

            PHP
        );
})->with([
    ['index', 'index.blade.php', 'IndexTest.php'],
    ['chirps/index', 'chirps/index.blade.php', 'Chirps/IndexTest.php'],
    ['chirps.index', 'chirps/index.blade.php', 'Chirps/IndexTest.php'],
    ['chirps-index.blade.php', 'chirps-index.blade.php', 'ChirpsIndexTest.php'],
    ['chirps_index.blade.php', 'chirps_index.blade.php', 'ChirpsIndexTest.php'],
    ['chirps/index.blade.php', 'chirps/index.blade.php', 'Chirps/IndexTest.php'],
]);

it('makes components with phpunit tests', function (string $name, string $alias, string $viewPath, string $testNamespace, string $testClass, string $testPath) {
    $this->artisan('make:volt', [
        'name' => $name,
        '--test' => true,
    ])->assertOk();

    $viewPath = resource_path('views/livewire/'.$viewPath);
    $testPath = base_path('tests/Feature/Livewire/'.$testPath);

    expect($viewPath)->toBeFile()
        ->and($testPath)->toBeFile()
        ->and(file_get_contents($testPath))->toBe(
            <<<PHP
            <?php

            namespace Tests\Feature\Livewire$testNamespace;

            use Livewire\Volt\Volt;
            use Tests\TestCase;

            class $testClass extends TestCase
            {
                public function test_it_can_render(): void
                {
                    \$component = Volt::test('$alias');

                    \$component->assertSee('');
                }
            }

            PHP
        );
})->with([
    ['index', 'index', 'index.blade.php', '', 'IndexTest', 'IndexTest.php'],
    ['chirps/index', 'chirps.index', 'chirps/index.blade.php', '\Chirps', 'IndexTest', 'Chirps/IndexTest.php'],
    ['chirps.index', 'chirps.index', 'chirps/index.blade.php', '\Chirps', 'IndexTest', 'Chirps/IndexTest.php'],
    ['chirps_index.blade.php', 'chirps_index', 'chirps_index.blade.php', '', 'ChirpsIndexTest', 'ChirpsIndexTest.php'],
    ['chirps-index.blade.php', 'chirps-index', 'chirps-index.blade.php', '', 'ChirpsIndexTest', 'ChirpsIndexTest.php'],
    ['chirps/index.blade.php', 'chirps.index', 'chirps/index.blade.php', '\Chirps', 'IndexTest', 'Chirps/IndexTest.php'],
]);

it('makes components with pest tests', function (string $name, string $alias, string $viewPath, string $testNamespace, string $testClass, string $testPath) {
    $this->artisan('make:volt', [
        'name' => $name,
        '--pest' => true,
    ])->assertOk();

    $viewPath = resource_path('views/livewire/'.$viewPath);
    $testPath = base_path('tests/Feature/Livewire/'.$testPath);

    expect($viewPath)->toBeFile()
        ->and($testPath)->toBeFile()
        ->and(file_get_contents($testPath))->toBe(
            <<<PHP
            <?php

            use Livewire\Volt\Volt;

            it('can render', function () {
                \$component = Volt::test('$alias');

                \$component->assertSee('');
            });

            PHP
        );
})->with([
    ['index', 'index', 'index.blade.php', '', 'IndexTest', 'IndexTest.php'],
    ['chirps/index', 'chirps.index', 'chirps/index.blade.php', '\Chirps', 'IndexTest', 'Chirps/IndexTest.php'],
    ['chirps.index', 'chirps.index', 'chirps/index.blade.php', '\Chirps', 'IndexTest', 'Chirps/IndexTest.php'],
    ['chirps_index.blade.php', 'chirps_index', 'chirps_index.blade.php', '', 'ChirpsIndexTest', 'ChirpsIndexTest.php'],
    ['chirps-index.blade.php', 'chirps-index', 'chirps-index.blade.php', '', 'ChirpsIndexTest', 'ChirpsIndexTest.php'],
    ['chirps/index.blade.php', 'chirps.index', 'chirps/index.blade.php', '\Chirps', 'IndexTest', 'Chirps/IndexTest.php'],
]);

afterEach(function () {
    collect([
        resource_path('views/livewire'),
        base_path('tests/Feature/Livewire'),
    ])->each(function (string $path) {
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }
    });
});
