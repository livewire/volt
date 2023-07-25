<?php

namespace Tests;

use Laravel\Folio\FolioServiceProvider;
use Livewire\Volt\VoltServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function defineEnvironment($app): void
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app): array
    {
        return [
            VoltServiceProvider::class,
            FolioServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }
}
