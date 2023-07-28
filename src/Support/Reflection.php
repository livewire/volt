<?php

namespace Livewire\Volt\Support;

use Closure;
use Illuminate\Support\Collection;
use Livewire\Volt\Exceptions\SignatureMismatchException;
use ReflectionClass;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;
use Throwable;

class Reflection
{
    /**
     * Get the method signature from the given parameters and return type.
     *
     * @param  array<int, ReflectionParameter>  $parameters
     */
    public static function toMethodSignature(string $name, array $parameters, ?ReflectionType $returnType): string
    {
        $parameters = collect($parameters)
            ->map(function (ReflectionParameter $parameter) {
                $type = static::getType($parameter->getType());

                $defaultValue = null;

                if ($parameter->isDefaultValueAvailable() &&
                    $parameter->isDefaultValueConstant()) {
                    $defaultValue = '= \\'.$parameter->getDefaultValueConstantName();
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $defaultValue = '= '.str_replace("\n", '', var_export($parameter->getDefaultValue(), true));
                }

                $variadic = $parameter->isVariadic() ? '...' : '';

                return trim("{$type} $variadic\${$parameter->getName()} {$defaultValue}");
            })->implode(', ');

        $returnType = static::getType($returnType);

        $returnType = $returnType ? ": $returnType" : '';

        return "function {$name}({$parameters})$returnType";
    }

    /**
     * Get the method signature of a closure as a string.
     */
    public static function toMethodSignatureFromClosure(string $name, Closure $closure): string
    {
        $reflectionFunction = new ReflectionFunction($closure);

        return static::toMethodSignature(
            $name,
            $reflectionFunction->getParameters(),
            $reflectionFunction->getReturnType(),
        );
    }

    /**
     * Get the method signature of combined closures as a string.
     *
     * @param  array<int, \Closure>  $closures
     */
    public static function toSingleMethodSignatureFromClosures(string $name, array $closures): string
    {
        if (empty($closures)) {
            return "function {$name}()";
        }

        [$closures, $parameters, $returnType] = [
            collect($closures),
            collect(),
            static::getReturnTypeFromClosure($closures[0]),
        ];

        collect($closures)
            ->each(function (Closure $closure) use (&$parameters, $returnType) {
                $closureParameters = static::getParametersFromClosure($closure);
                $closureReturnType = static::getReturnTypeFromClosure($closure);

                $parameters->intersectByKeys($closureParameters)
                    ->filter(function (ReflectionParameter $parameter, string $name) use ($closureParameters) {
                        return static::getType($parameter->getType())
                            !== static::getType($closureParameters[$name]->getType());
                    })->whenNotEmpty(function () {
                        throw new SignatureMismatchException('Closures parameters with the same name must have the same type.');
                    });

                if (static::getType($returnType) !== static::getType($closureReturnType)) {
                    throw new SignatureMismatchException('Closures must have the same return type.');
                }

                $parameters = $parameters->merge($closureParameters);
            });

        return Reflection::toMethodSignature($name, $parameters->all(), $returnType);
    }

    /**
     * Get the reflection parameters of the given closure keyed by their name.
     *
     * @return \Illuminate\Support\Collection<string, \ReflectionParameter>
     */
    protected static function getParametersFromClosure(?Closure $closure): Collection
    {
        return is_null($closure) ? collect() : collect((new ReflectionFunction($closure))->getParameters())
            ->mapWithKeys(function (ReflectionParameter $parameter) {
                return [$parameter->getName() => $parameter];
            });
    }

    /**
     * Get the return type of the given closure.
     */
    protected static function getReturnTypeFromClosure(?Closure $closure): ?ReflectionType
    {
        return ! is_null($closure) ? (new ReflectionFunction($closure))->getReturnType() : null;
    }

    /**
     * Get the type of the given parameter.
     */
    protected static function getType(?ReflectionType $type, bool $nextDisjunctive = false): ?string
    {
        if ($type === null) {
            return null;
        }

        if ($type instanceof ReflectionUnionType || $type instanceof ReflectionIntersectionType) {
            $name = collect($type->getTypes())->map(
                fn (ReflectionType $type) => self::getType($type, true)
            )->implode($type instanceof ReflectionUnionType ? '|' : '&');

            return $nextDisjunctive ? "({$name})" : $name;
        }

        $name = $type->isBuiltin() ? $type->getName() : '\\'.$type->getName();

        return $name !== 'mixed' && $type->allowsNull()
                ? "?{$name}"
                : $name;
    }

    /**
     * Get the signature of the given attributes.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function toAttributesSignature(array $attributes): string
    {
        return collect($attributes)
            ->map(function (array $parameters, string $name) {
                return sprintf(
                    '#[\%s(%s)]',
                    $name,
                    collect($parameters)
                        ->map(function (mixed $parameter, string|int $key) {
                            if (is_string($key)) {
                                return sprintf('%s: %s', $key, var_export($parameter, true));
                            }

                            return var_export($parameter, true);
                        })
                        ->implode(', ')
                );
            })->map(fn (string $code) => '    '.$code)->implode("\n");
    }

    /**
     * Overwrite the message of an exception.
     */
    public static function setExceptionMessage(Throwable $exception, string $message): void
    {
        $reflectionClass = new ReflectionClass($exception);

        $reflectionProperty = tap($reflectionClass->getProperty('message'))->setAccessible(true);

        $reflectionProperty->setValue($exception, $message);
    }
}
