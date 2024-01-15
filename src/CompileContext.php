<?php

namespace Livewire\Volt;

use Closure;
use Illuminate\Support\Str;

class CompileContext
{
    /**
     * The current global instance of the context, if any.
     */
    protected static ?self $instance = null;

    /**
     * Create a new compile context instance.
     */
    public function __construct(
        public ?string $path,
        public array $variables,
        public array $state,
        public ?string $layout,
        public Closure|string|null $title,
        public ?Closure $listeners,
        public array $inlineListeners,
        public Closure|array $rules,
        public array $messages,
        public array $validationAttributes,
        public ?string $paginationView,
        public ?string $paginationTheme,
        public array $uses,
        public ?Closure $viewData,
        public ?Closure $placeholder,

        // Hooks...
        public ?Closure $boot,
        public ?Closure $booted,
        public Closure $mount,
        public ?Closure $dehydrate,
        public array $dehydrateProperty,
        public ?Closure $hydrate,
        public array $hydrateProperty,
        public array $updating,
        public array $updated,
    ) {
        //
    }

    /**
     * Get the current compile context instance or create a new one.
     */
    public static function instance(): static
    {
        return static::$instance ??= static::make();
    }

    /**
     * Create a new, empty compile context instance.
     */
    public static function make(): static
    {
        return new static(
            path: null,
            variables: [],
            state: [],
            layout: null,
            title: null,
            listeners: null,
            inlineListeners: [],
            rules: [],
            messages: [],
            validationAttributes: [],
            paginationView: null,
            paginationTheme: null,
            uses: [],
            viewData: null,
            placeholder: null,

            // Hooks...
            boot: null,
            booted: null,
            mount: fn () => null,
            dehydrate: null,
            dehydrateProperty: [],
            hydrate: null,
            hydrateProperty: [],
            updating: [],
            updated: [],
        );
    }

    /**
     * Resolve the event to listener array for the compile context using the given component.
     *
     * @return array<string, string>
     */
    public function resolveListeners(Component $component): array
    {
        return collect(
            call_user_func(Closure::bind($this->listeners, $component, $component::class), $component)
        )->mapWithKeys(function (string $listener, string $eventName) use ($component) {
            return [preg_replace_callback('/\{.*?\}/s', function (array $matches) use ($component) {
                return data_get($component, Str::between($matches[0], '{', '}'));
            }, $eventName) => $listener];
        })->all();
    }

    /**
     * Register an event listener on the context.
     */
    public function listen(Closure|array|string $listenersOrEventName, Closure|string|null $handler = null): void
    {
        if (is_array($listenersOrEventName)) {
            $listeners = $this->registerInlineListeners($listenersOrEventName);

            $this->listen(fn () => $listeners);

            return;
        } elseif (is_string($listenersOrEventName) && is_string($handler)) {
            $this->listen(fn () => [$listenersOrEventName => $handler]);

            return;
        }

        if (is_string($listenersOrEventName) && $handler instanceof Closure) {
            $this->inlineListeners[$listenersOrEventName] = $handler;

            $this->listen(
                $listenersOrEventName, InlineListenerName::for($listenersOrEventName)
            );

            return;
        }

        $previous = $this->listeners ?? fn () => [];

        $this->listeners = fn (Component $component) => array_merge(
            Closure::bind($previous, $component, $component::class)($component),
            Closure::bind($listenersOrEventName, $component, $component::class)($component),
        );
    }

    /**
     * Register the inline listeners in the given array, returning the non-inline listeners.
     */
    protected function registerInlineListeners(array $listeners): array
    {
        [$callables, $uncallables] = collect($listeners)->partition(
            fn (Closure|string $listener) => $listener instanceof Closure
        );

        $callables->each(function (Closure $callable, string $eventName) {
            $this->listen($eventName, $callable);
        });

        return $uncallables->all();
    }

    /**
     * Flush the current global instance of the compile context.
     */
    public static function flush(): void
    {
        static::$instance = null;
    }
}
