<?php

use Livewire\Volt\FragmentAlias;

it('may encode a component', function () {
    $alias = FragmentAlias::encode('my-component', '/app/my-path', '/app');

    expect($alias)->toBe('volt-anonymous-fragment-eyJuYW1lIjoibXktY29tcG9uZW50IiwicGF0aCI6Im15LXBhdGgifQ==');
});

it('may decode a component', function () {
    $component = FragmentAlias::decode('volt-anonymous-fragment-eyJuYW1lIjoibXktY29tcG9uZW50IiwicGF0aCI6Im15LXBhdGgifQ==', '/app');

    expect($component)->toBe([
        'name' => 'my-component',
        'path' => '/app/my-path',
    ]);
});

it('may fail to decode a component when the alias is not a fragment', function () {
    $component = FragmentAlias::decode('xyz');

    expect($component)->toBeNull();
});

it('may fail to decode a component when hash is wrong', function () {
    $component = FragmentAlias::decode('volt-anonymous-fragment-xyz');

    expect($component)->toBeNull();
});
