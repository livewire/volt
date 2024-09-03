<?php

namespace Livewire\Volt\Precompilers;

use Illuminate\Support\Facades\Blade;
use Livewire\Volt\Exceptions\ReturnNewClassExecutionEndingException;
use Livewire\Volt\MountedDirectories;
use Livewire\Volt\Volt;

class ExtractTemplate
{
    use Concerns\ExtractsImports;

    /**
     * Create a new precompiler instance.
     */
    public function __construct(protected MountedDirectories $mountedDirectories) {}

    /**
     * Extracts the template from the given blade view content.
     */
    public function __invoke(string $template): string
    {
        if (! $this->shouldExtractTemplate($template)) {
            return $template;
        }

        $this->ensureNoReturnNewClassExecutionEnding($template);

        return $this->imports($template).
               $this->html($template);
    }

    /**
     * Determine if the current view is a Volt component.
     */
    protected function shouldExtractTemplate(string $template): bool
    {
        if (is_null($path = Blade::getPath())) {
            return false;
        }

        return $this->mountedDirectories->isWithinMountedDirectory($path);
    }

    /**
     * Extract the HTML from the given template.
     */
    protected function html(string $template): string
    {
        $template = trim(preg_replace('/<\?php\s*(.*?)\s*\?>/s', '', $template));

        return str($template)->beforeLast('<?php')->trim()->value();
    }

    /**
     * Ensures the given template does not contain any "return new class" execution ending.
     */
    protected function ensureNoReturnNewClassExecutionEnding(string $template): void
    {
        if (preg_match('/return\s+new\s+class\s+extends\s+Component/', $template) > 0) {
            throw new ReturnNewClassExecutionEndingException;
        }
    }
}
