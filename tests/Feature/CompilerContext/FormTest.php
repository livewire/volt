<?php

use Livewire\Form;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Property;

use function Livewire\Volt\form;

class ProductForm extends Form
{
}

it('may be defined', function () {
    $context = CompileContext::instance();

    form(ProductForm::class);
    form(ProductForm::class, 'userForm');

    $types = array_map(fn (Property $property) => $property->type, $context->state);

    expect($context->state)->toHaveKeys(['form', 'userForm'])
        ->and($types)->each->toBe(ProductForm::class);
});
