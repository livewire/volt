<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\usesPagination;
use Livewire\WithPagination;

it('is not used by default', function () {
    $context = CompileContext::instance();

    expect($context->traits)->not->toContain(WithPagination::class);
});

it('may be used', function () {
    $context = CompileContext::instance();

    usesPagination();

    expect($context->traits)->toContain(WithPagination::class)
        ->and($context->paginationView)->toBeNull()
        ->and($context->paginationTheme)->toBeNull();
});

it('may have a custom view', function () {
    $context = CompileContext::instance();

    usesPagination(view: 'custom-view');

    expect($context->paginationView)->toBe('custom-view');
});

it('may have a custom theme', function () {
    $context = CompileContext::instance();

    usesPagination(theme: 'bootstrap');

    expect($context->paginationTheme)->toBe('bootstrap');
});
