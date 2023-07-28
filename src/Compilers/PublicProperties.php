<?php

namespace Livewire\Volt\Compilers;

use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;
use Livewire\Volt\Property;
use Livewire\Volt\Support\Reflection;

class PublicProperties implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        return collect($context->state)
            ->map(function (Property $property, string $name) {
                $attributes = Reflection::toAttributesSignature($property->attributes);
                $type = $property->type ? "\\{$property->type} " : '';

                $code = <<<PHP
                        public $type\${$name};

                    PHP;

                if (! empty($attributes)) {
                    $code = $attributes."\n".$code;
                }

                return $code;
            })->all();
    }
}
