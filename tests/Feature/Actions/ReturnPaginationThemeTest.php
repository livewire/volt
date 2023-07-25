<?php

use Livewire\Volt\Actions\ReturnPaginationTheme;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('returns the pagination theme', function () {
    $context = CompileContext::make();

    $context->paginationTheme = 'bootstrap';

    $component = new class extends Component
    {
    };

    $result = (new ReturnPaginationTheme)->execute($context, $component, []);

    expect($result)->toBe('bootstrap');
});
