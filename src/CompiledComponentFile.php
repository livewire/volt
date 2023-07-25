<?php

namespace Livewire\Volt;

use Illuminate\Support\Facades\File;

class CompiledComponentFile
{
    /**
     * The path to the compiled component.
     */
    protected ?string $compiledPath = null;

    /**
     * Create a new component file instance.
     */
    public function __construct(protected string $originalPath, protected ?string $componentName = null)
    {
    }

    /**
     * Determine if the compiled component exists.
     */
    public function exists(): bool
    {
        return File::exists($this->path());
    }

    /**
     * Delete the compiled component file.
     */
    public function delete(): void
    {
        if ($this->exists()) {
            File::delete($this->path());
        }
    }

    /**
     * Delete the compiled component file if it needs recompilation.
     */
    public function deleteIfNeedsRecompilation(): void
    {
        if ($this->needsRecompilation()) {
            $this->delete();
        }
    }

    /**
     * Determine if the component needs recompilation.
     */
    public function needsRecompilation(): bool
    {
        return $this->exists() && File::lastModified($this->originalPath) >= File::lastModified($this->path());
    }

    /**
     * Get the path to the compiled component with the given original template path.
     */
    public function path(): string
    {
        if (! $this->compiledPath) {
            $this->compiledPath = app('config')['view.compiled'].
                        DIRECTORY_SEPARATOR.
                        md5($this->componentName.CompilerVersion::NUMBER).
                        '.php';
        }

        return $this->compiledPath;
    }
}
