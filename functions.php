<?php

namespace Livewire\Volt;

use Closure;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Livewire\Form;
use Livewire\Volt\Exceptions\TraitOrInterfaceNotFound;
use Livewire\Volt\Methods\ActionMethod;
use Livewire\Volt\Methods\ComputedMethod;
use Livewire\Volt\Methods\JsMethod;
use Livewire\Volt\Methods\ProtectedMethod;
use Livewire\Volt\Options\RuleOptions;
use Livewire\Volt\Options\StateOptions;
use Livewire\Volt\Options\UsesOptions;

/**
 * Define the component's state.
 */
function state(mixed ...$properties): StateOptions
{
    if (array_key_exists(0, $properties)) {
        if (count($properties) === 1) {
            if (is_string($properties[0])) {
                $properties = [$properties[0] => null];
            } elseif (Arr::isAssoc($properties[0])) {
                $properties = array_map(
                    static fn ($key, $value) => is_numeric($key) ? [$value => null] : [$key => $value],
                    array_keys($properties[0]),
                    $properties[0]
                );

                $properties = array_merge(...$properties);
            } else {
                $properties = array_fill_keys($properties[0], null);
            }
        } elseif (count($properties) === 2) {
            $properties = [$properties[0] => $properties[1]];
        }
    }

    CompileContext::instance()->state = array_merge(
        CompileContext::instance()->state,
        $properties = array_map(fn ($value) => Property::make($value), $properties)
    );

    return new StateOptions($properties);
}

/**
 * Define a new "protected" method on the component.
 */
function protect(Closure $closure): ProtectedMethod
{
    return ProtectedMethod::make($closure);
}

/**
 * Define a new "placeholder" method on the component.
 */
function placeholder(Closure|Renderable|string $closure): void
{
    if (! $closure instanceof Closure) {
        $closure = fn () => $closure;
    }

    CompileContext::instance()->placeholder = $closure;
}

/**
 * Define a new "action" method on the component.
 */
function action(Closure $closure): ActionMethod
{
    return ActionMethod::make($closure);
}

/**
 * Define a "computed" property for the component.
 */
function computed(Closure $closure): ComputedMethod
{
    return ComputedMethod::make($closure);
}

/**
 * Define the component's layout.
 */
function layout(string $layout): void
{
    CompileContext::instance()->layout = $layout;
}

/**
 * Define the component's title.
 */
function title(Closure|string $title): void
{
    CompileContext::instance()->title = $title;
}

/**
 * Define the component's view data.
 */
function with(mixed ...$data): void
{
    if (array_key_exists(0, $data)) {
        if (count($data) === 1) {
            if ($data[0] instanceof Closure) {
                CompileContext::instance()->viewData = $data[0];

                return;
            }

            if (is_string($data[0])) {
                $data = [$data[0] => null];
            } elseif (Arr::isAssoc($data[0])) {
                $data = $data[0];
            } else {
                $data = array_fill_keys($data[0], null);
            }
        } elseif (count($data) === 2) {
            $data = [$data[0] => $data[1]];
        }
    }

    CompileContext::instance()->viewData = fn () => $data;
}

/**
 * Define a "form" property for the component.
 *
 * @param  class-string<Form>  $class
 *
 * @throws \InvalidArgumentException
 */
function form(string $class, string $propertyName = 'form'): void
{
    if (! is_subclass_of($class, Form::class)) {
        throw new InvalidArgumentException('The given class must be a Livewire form object.');
    }

    state($propertyName)->type($class);
}

/**
 * Define a new "js" method on the component.
 */
function js(Closure|string $closureOrCode): JsMethod
{
    return JsMethod::make($closureOrCode);
}

/**
 * Register a component's boot hook.
 */
function boot(Closure $hook): void
{
    CompileContext::instance()->boot = $hook;
}

/**
 * Register a component's booted hook.
 */
function booted(Closure $hook): void
{
    CompileContext::instance()->booted = $hook;
}

/**
 * Register a component's mount hook.
 */
function mount(Closure $hook): void
{
    CompileContext::instance()->mount = $hook;
}

/**
 * Register a component's hydrate hook.
 */
function hydrate(Closure|array ...$properties): void
{
    if (count($properties) === 1 && array_key_exists(0, $properties)) {
        if ($properties[0] instanceof Closure) {
            CompileContext::instance()->hydrate = $properties[0];

            return;
        }

        $properties = $properties[0];
    }

    CompileContext::instance()->hydrateProperty = array_merge(
        CompileContext::instance()->hydrateProperty,
        $properties,
    );
}

/**
 * Register a component's dehydrate hook.
 */
function dehydrate(Closure|array ...$properties): void
{
    if (count($properties) === 1 && array_key_exists(0, $properties)) {
        if ($properties[0] instanceof Closure) {
            CompileContext::instance()->dehydrate = $properties[0];

            return;
        }

        $properties = $properties[0];
    }

    CompileContext::instance()->dehydrateProperty = array_merge(
        CompileContext::instance()->dehydrateProperty,
        $properties,
    );
}

/**
 * Register a component's updating hook.
 */
function updating(Closure|array ...$properties): void
{
    if (count($properties) === 1 && array_key_exists(0, $properties)) {
        $properties = $properties[0];
    }

    CompileContext::instance()->updating = array_merge(
        CompileContext::instance()->updating,
        $properties,
    );
}

/**
 * Register a component's updated hook.
 */
function updated(Closure|array ...$properties): void
{
    if (count($properties) === 1 && array_key_exists(0, $properties)) {
        $properties = $properties[0];
    }

    CompileContext::instance()->updated = array_merge(
        CompileContext::instance()->updated,
        $properties,
    );
}

/**
 * Register an event listener on the component.
 */
function on(Closure|array|string ...$listeners): void
{
    if (count($listeners) === 1 && array_key_exists(0, $listeners)) {
        $listeners = $listeners[0];
    }

    CompileContext::instance()->listen($listeners);
}

/**
 * Define the component's validation rules.
 */
function rules(mixed ...$rules): RuleOptions
{
    if (count($rules) === 1 && array_key_exists(0, $rules)) {
        if ($rules[0] instanceof Closure) {
            CompileContext::instance()->rules = $rules[0];

            return new RuleOptions;
        }

        $rules = $rules[0];
    }

    CompileContext::instance()->rules = array_merge(
        CompileContext::instance()->rules,
        $rules,
    );

    return new RuleOptions;
}

/**
 * Indicate that the component supports file uploads.
 */
function usesFileUploads(): UsesOptions
{
    return (new UsesOptions)->usesFileUploads();
}

/**
 * Indicate that the component supports pagination.
 */
function usesPagination(?string $view = null, ?string $theme = null): UsesOptions
{
    return (new UsesOptions)->usesPagination($view, $theme);
}

/**
 * Indicate that the component should use the given trait or interface.
 */
function uses(array|string $name): UsesOptions
{
    $uses = Arr::wrap($name);

    foreach ($uses as $use) {
        if (! trait_exists($use) && ! interface_exists($use)) {
            throw new TraitOrInterfaceNotFound($use);
        }
    }

    CompileContext::instance()->uses = array_values(array_unique(array_merge(
        CompileContext::instance()->uses,
        Arr::wrap($name),
    )));

    return new UsesOptions;
}
