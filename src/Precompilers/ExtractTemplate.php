<?php

namespace Livewire\Volt\Precompilers;

use Illuminate\Support\Facades\Blade;
use Livewire\Volt\Exceptions\ReturnNewClassExecutionEndingException;
use Livewire\Volt\MountedDirectories;
use Livewire\Volt\Volt;

class ExtractTemplate
{
    /**
     * Create a new precompiler instance.
     */
    public function __construct(protected MountedDirectories $mountedDirectories)
    {
    }

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
        return $this->mountedDirectories->isWithinMountedDirectory(Blade::getPath());
    }

    /**
     * Extract the PHP "use" statements from the given template.
     */
    protected function imports(string $template): string
    {
        $phpScript = trim(preg_replace('/.*<\?php\s*(.*?)\s*\?>.*/s', '$1', $template));
        $phpScript = trim(preg_replace('/^(?!use\s+.*?;).*$/m', '', $phpScript));
        $phpScript = trim(preg_replace('/^use\s+function\s+Livewire\\\\Volt.*$/m', '', $phpScript));

        if (! empty($phpScript)) {
            $phpScript = '<?php'."\n\n".$phpScript."\n\n".'?>'."\n\n";
        }

        return $phpScript;
    }

    /**
     * Extract the HTML from the given template.
     */
    protected function html(string $template): string
    {
        return trim(preg_replace('/<\?php\s*(.*?)\s*\?>/s', '', $template));
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
