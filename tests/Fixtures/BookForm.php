<?php

namespace Tests\Fixtures;

use Livewire\Attributes\Rule;
use Livewire\Form;

class BookForm extends Form
{
    #[Rule('required|min:5')]
    public $title = '';
}
