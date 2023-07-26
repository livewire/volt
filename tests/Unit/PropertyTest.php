<?php

use Livewire\Attributes\Url;
use Livewire\Volt\Property;

test('has attribute', function () {
    $property = Property::make('value');
    expect($property->hasAttribute(Url::class))->toBeFalse();

    $property->attribute(Url::class);
    expect($property->hasAttribute(Url::class))->toBeTrue();
});
