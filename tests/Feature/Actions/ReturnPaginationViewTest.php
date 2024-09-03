<?php

use Livewire\Volt\Actions\ReturnPaginationView;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('returns the pagination view', function () {
    $context = CompileContext::make();

    $context->paginationView = 'my-custom-view';

    $component = new class extends Component {};

    $result = (new ReturnPaginationView)->execute($context, $component, []);

    expect($result)->toBe('my-custom-view');
});
