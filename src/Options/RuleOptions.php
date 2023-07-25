<?php

namespace Livewire\Volt\Options;

use Livewire\Volt\CompileContext;

class RuleOptions
{
    /**
     * Define the component's validation messages.
     */
    public function messages(array|string ...$messages): static
    {
        if (count($messages) === 1 && array_key_exists(0, $messages)) {
            $messages = $messages[0];
        }

        CompileContext::instance()->messages = array_merge(
            CompileContext::instance()->messages,
            $messages,
        );

        return $this;
    }

    /**
     * Define the component's validation attributes.
     */
    public function attributes(array|string ...$attributes): static
    {
        if (count($attributes) === 1 && array_key_exists(0, $attributes)) {
            $attributes = $attributes[0];
        }

        CompileContext::instance()->validationAttributes = array_merge(
            CompileContext::instance()->validationAttributes,
            $attributes,
        );

        return $this;
    }
}
