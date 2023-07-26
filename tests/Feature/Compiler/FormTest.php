<?php

use Livewire\Form;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Compiler;
use function Livewire\Volt\form;

class UserForm extends Form
{
}

it('may be defined', function () {
    form(UserForm::class);
    form(UserForm::class, 'userForm');

    $code = Compiler::contextToString(CompileContext::instance());

    expect($code)->toContain(<<<'PHP'
        public \UserForm $form;

        public \UserForm $userForm;
    PHP
    );
});

it('can not be defined with non-form classes', function () {
    form(static::class);
})->throws(InvalidArgumentException::class, 'The given class must be a Livewire form object.');
