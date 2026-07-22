<?php

use Livewire\Volt\ComponentFactory;
use Livewire\Volt\ComponentResolver;
use Livewire\Volt\FragmentAlias;

it('resolves fragment paths within a mounted directory', function () {
    $path = __DIR__.'/../Feature/resources/views/functional-api-pages/page-with-fragment.blade.php';
    $alias = FragmentAlias::encode('fragment', $path);

    $factory = Mockery::mock(ComponentFactory::class);
    $factory->shouldReceive('make')
        ->once()
        ->with($alias, realpath($path))
        ->andReturn('ResolvedComponent');

    expect((new ComponentResolver($factory))->resolve($alias, [dirname($path)]))
        ->toBe('ResolvedComponent');
});

it('resolves fragment paths within a configured view directory', function () {
    $path = __DIR__.'/../Feature/resources/views/functional-api-pages/page-with-fragment.blade.php';
    $alias = FragmentAlias::encode('fragment', $path);

    config()->set('view.paths', [dirname($path)]);

    $factory = Mockery::mock(ComponentFactory::class);
    $factory->shouldReceive('make')
        ->once()
        ->with($alias, realpath($path))
        ->andReturn('ResolvedComponent');

    expect((new ComponentResolver($factory))->resolve($alias, []))
        ->toBe('ResolvedComponent');
});

it('resolves fragment paths within the compiled view directory', function () {
    $path = __FILE__;
    $alias = FragmentAlias::encode('fragment', $path);

    config()->set('view.compiled', __DIR__);

    $factory = Mockery::mock(ComponentFactory::class);
    $factory->shouldReceive('make')
        ->once()
        ->with($alias, realpath($path))
        ->andReturn('ResolvedComponent');

    expect((new ComponentResolver($factory))->resolve($alias, []))
        ->toBe('ResolvedComponent');
});

it('does not resolve fragment paths outside the trusted directories', function () {
    $factory = Mockery::mock(ComponentFactory::class);
    $factory->shouldNotReceive('make');

    $alias = FragmentAlias::encode('proof', __FILE__);

    config()->set('view.paths', [
        __DIR__.'/../Feature/resources/views',
    ]);

    expect((new ComponentResolver($factory))->resolve($alias, [
        __DIR__.'/../Feature/resources/views/functional-api-pages',
    ]))->toBeNull();
});
