<?php

namespace Livewire\Volt\Options;

use Livewire\Volt\CompileContext;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use function Livewire\Volt\uses;

class UsesOptions
{
    /**
     * Indicate that the component should be compiled with file upload support.
     */
    public function usesFileUploads(): static
    {
        return uses(WithFileUploads::class);
    }

    /**
     * Indicate that the component should be compiled with pagination support.
     */
    public function usesPagination(?string $view = null, ?string $theme = null): static
    {
        CompileContext::instance()->paginationView = $view;
        CompileContext::instance()->paginationTheme = $theme;

        return uses(WithPagination::class);
    }
}
