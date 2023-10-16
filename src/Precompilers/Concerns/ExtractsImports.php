<?php

namespace Livewire\Volt\Precompilers\Concerns;

trait ExtractsImports
{
    /**
     * Extract the PHP "use" statements from the given template.
     */
    protected function imports(string $template): string
    {
        preg_match_all('/<\?php\s*(.*?)\s*\?>/s', $template, $matches);

        $script = collect($matches[1])
            ->map(fn (string $script) => trim($script))
            ->reject(fn (string $script) => empty($script))
            ->implode("\n");

        $script = trim(preg_replace('/^(?!use\s+.*?;).*$/m', '', $script));
        $script = trim(preg_replace('/^use\s+function\s+Livewire\\\\Volt.*$/m', '', $script));

        if (! empty($script)) {
            $script = '<?php'."\n\n".$script."\n\n".'?>'."\n\n";
        }

        return $script;
    }
}
