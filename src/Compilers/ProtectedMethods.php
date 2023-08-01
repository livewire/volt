<?php

namespace Livewire\Volt\Compilers;

use Illuminate\Support\Collection;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;
use Livewire\Volt\Methods\Method;
use Livewire\Volt\Methods\ReflectionMethod;
use Livewire\Volt\Support\Reflection;

class ProtectedMethods implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        return $this->compileMethods($context)
            ->when(! empty($context->rules), fn (Collection $collection) => $collection->add(<<<PHP
                protected function rules()
                {
                    return (new Actions\ReturnRules)->execute(static::\$__context, \$this, []);
                }

            PHP,
            ))->when(! empty($context->messages), fn (Collection $collection) => $collection->add(<<<PHP
                protected function messages()
                {
                    return (new Actions\ReturnValidationMessages)->execute(static::\$__context, \$this, []);
                }

            PHP,
            ))->when(! empty($context->validationAttributes), fn (Collection $collection) => $collection->add(<<<PHP
                protected function validationAttributes()
                {
                    return (new Actions\ReturnValidationAttributes)->execute(static::\$__context, \$this, []);
                }

            PHP,
            ))->all();
    }

    /**
     * Compile the user-defined protected methods.
     */
    protected function compileMethods(CompileContext $context): Collection
    {
        return collect($context->variables)
            ->whereInstanceOf(Method::class)
            ->map(fn (Method $method) => $method->reflection())
            ->filter(fn (ReflectionMethod $reflection) => $reflection->isProtected())
            ->map(function (ReflectionMethod $reflection, string $methodName) {
                $attributes = Reflection::toAttributesSignature($reflection->attributes);
                $signature = Reflection::toMethodSignatureFromClosure($methodName, $reflection->closure);

                $return = str_ends_with($signature, 'void') ? '' : 'return ';

                return <<<PHP
                    $attributes
                    protected $signature
                    {
                        \$arguments = [static::\$__context, \$this, func_get_args()];

                        $return(new Actions\CallMethod('$methodName'))->execute(...\$arguments);
                    }

                PHP;
            });
    }
}
