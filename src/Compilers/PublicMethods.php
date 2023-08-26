<?php

namespace Livewire\Volt\Compilers;

use Closure;
use Illuminate\Support\Collection;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiler;
use Livewire\Volt\InlineListenerName;
use Livewire\Volt\Methods\Method;
use Livewire\Volt\Methods\ReflectionMethod;
use Livewire\Volt\Support\Reflection;

class PublicMethods implements Compiler
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompileContext $context): array
    {
        return collect()
            ->merge($this->compileMethods($context))
            ->merge($this->compileHooks($context))
            ->merge($this->compilePropertyHooks($context))
            ->merge($this->compileListenersMethod($context))
            ->merge($this->compileListeners($context))
            ->merge($this->compilePaginationView($context))
            ->merge($this->compilePlaceholder($context))
            ->toArray();
    }

    /**
     * Compile the closure variables defined on the compile context.
     */
    protected function compileMethods(CompileContext $context): Collection
    {
        return collect($context->variables)
            ->whereInstanceOf(Method::class)
            ->map(fn (Method $method) => $method->reflection())
            ->filter(fn (ReflectionMethod $reflection) => $reflection->isPublic())
            ->map(function (ReflectionMethod $reflection, string $methodName) {
                $attributes = Reflection::toAttributesSignature($reflection->attributes);
                $signature = Reflection::toMethodSignatureFromClosure($methodName, $reflection->closure);

                $return = str_ends_with($signature, 'void') ? '' : 'return ';

                $code = <<<PHP
                    public $signature
                    {
                        \$arguments = [static::\$__context, \$this, func_get_args()];

                        $return(new Actions\CallMethod('$methodName'))->execute(...\$arguments);
                    }

                PHP;

                if (! empty($attributes)) {
                    $code = $attributes."\n".$code;
                }

                return $code;
            });
    }

    /**
     * Compile the hooks defined on the compile context.
     */
    protected function compileHooks(CompileContext $context): Collection
    {
        return collect(['boot', 'booted', 'hydrate', 'dehydrate'])
            ->filter(fn (string $hook) => $context->{$hook} !== null)
            ->map(function (string $hook) use ($context) {
                $signature = Reflection::toMethodSignatureFromClosure($hook, $context->{$hook});

                return <<<PHP
                public {$signature}
                {
                    \$arguments = [static::\$__context, \$this, func_get_args()];

                    return (new Actions\CallHook('$hook'))->execute(...\$arguments);
                }

            PHP;
            });
    }

    /**
     * Compile the property hooks defined on the compile context.
     */
    protected function compilePropertyHooks(CompileContext $context): Collection
    {
        return collect(['hydrateProperty', 'dehydrateProperty', 'updating', 'updated'])
            ->filter(fn (string $hook) => ! empty($context->{$hook}))
            ->map(fn (string $hook) => <<<PHP
                public function {$hook}(\$name)
                {
                    \$arguments = [static::\$__context, \$this, array_slice(func_get_args(), 1)];

                    return (new Actions\CallPropertyHook('$hook', \$name))->execute(...\$arguments);
                }

            PHP
            );
    }

    /**
     * Compile the listener definition method for the component.
     */
    protected function compileListenersMethod(CompileContext $context): Collection
    {
        if ($context->listeners === null) {
            return collect();
        }

        return collect([<<<PHP
            public function getListeners()
            {
                \$arguments = [static::\$__context, \$this, func_get_args()];

                return (new Actions\ResolveListeners)->execute(...\$arguments);
            }

        PHP
        ]);
    }

    /**
     * Compile the listener methods for closure based listeners defined on the context.
     */
    protected function compileListeners(CompileContext $context): Collection
    {
        return collect($context->inlineListeners)->map(function (Closure $handler, string $eventName) {
            $signature = Reflection::toMethodSignatureFromClosure(
                InlineListenerName::for($eventName), $handler
            );

            return <<<PHP
                public {$signature}
                {
                    \$arguments = [static::\$__context, \$this, func_get_args()];

                    return (new Actions\CallListener('{$eventName}'))->execute(...\$arguments);
                }

            PHP;
        });
    }

    /**
     * Compile the pagination view method based on what's defined on the context.
     */
    protected function compilePaginationView(CompileContext $context): Collection
    {
        if ($context->paginationView === null) {
            return collect();
        }

        return collect(<<<PHP
            public function paginationView()
            {
                \$arguments = [static::\$__context, \$this, func_get_args()];

                return (new Actions\ReturnPaginationView())->execute(...\$arguments);
            }
        PHP,
        );
    }

    /**
     * Compile the placeholder method based on what's defined on the context.
     */
    protected function compilePlaceholder(CompileContext $context): Collection
    {
        if ($context->placeholder === null) {
            return collect();
        }

        return collect(<<<PHP
            public function placeholder()
            {
                return (new Actions\ReturnPlaceholder())->execute(static::\$__context, \$this, []);
            }
        PHP,
        );
    }
}
