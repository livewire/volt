<?php

namespace Livewire\Volt;

use Illuminate\Support\Facades\File;
use Livewire\Volt\Compilers\Mount;
use Livewire\Volt\Compilers\ProtectedMethods;
use Livewire\Volt\Compilers\ProtectedProperties;
use Livewire\Volt\Compilers\PublicMethods;
use Livewire\Volt\Compilers\PublicProperties;
use Livewire\Volt\Compilers\Traits;

class Compiler
{
    /**
     * Compile and instantiate a new component instance.
     */
    public static function compile(CompiledComponentFile $file, CompileContext $context): bool
    {
        $file->deleteIfNeedsRecompilation();

        if (! $file->exists()) {
            File::put($file->path(), static::contextToString($context));

            return true;
        }

        return false;
    }

    /**
     * Compile the given context into "valid" PHP code as string.
     */
    public static function contextToString(CompileContext $context): string
    {
        $code = collect([
            Traits::class,
            PublicProperties::class,
            ProtectedProperties::class,
            Mount::class,
            PublicMethods::class,
            ProtectedMethods::class,
        ])->map(function (string $compiler) use ($context) {
            return (new $compiler)->compile($context);
        })->flatten()->values()->implode("\n");

        $interfaces = collect((new Compilers\Interfaces)->compile($context))->implode(', ');

        return str(<<<PHP
            <?php

            use Livewire\Volt\Actions;
            use Livewire\Volt\CompileContext;
            use Livewire\Volt\Contracts\Compiled;
            use Livewire\Volt\Component;

            new class extends Component implements $interfaces
            {
                public static CompileContext \$__context;

            $code
            };
            PHP
        )->trim();
    }
}
