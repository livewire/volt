<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\form;
use Livewire\Volt\Property;
use Livewire\Form;

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
