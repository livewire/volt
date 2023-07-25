<?php

namespace Livewire\Volt\Compilers;

use Closure;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;
use Livewire\Volt\Exceptions\SignatureMismatchException;
use Livewire\Volt\Property;
use Livewire\Volt\Support\Reflection;

class Mount implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        $signature = $this->mountMethodSignature($context);

        return [
            <<<PHP
                public $signature
                {
                    (new Actions\InitializeState)->execute(static::\$__context, \$this, get_defined_vars());

                    (new Actions\CallHook('mount'))->execute(static::\$__context, \$this, get_defined_vars());
                }

            PHP
        ];
    }

    /**
     * Get the mount method signature, and ensure the mount and state closures have the same signatures.
     */
    protected function mountMethodSignature(CompileContext $context): string
    {
        try {
            return Reflection::toSingleMethodSignatureFromClosures(
                'mount',
                collect([$context->mount])
                    ->merge(collect($context->state)->map(fn (Property $property) => $property->value))
                    ->filter(fn (mixed $value) => $value instanceof Closure)
                    ->all(),
            );
        } catch (SignatureMismatchException $e) {
            Reflection::setExceptionMessage($e, 'Mount and state closures must have the same signature.');

            throw $e;
        }
    }
}
