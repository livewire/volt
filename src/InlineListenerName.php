<?php

namespace Livewire\Volt;

use Illuminate\Support\Str;

class InlineListenerName
{
    /**
     * Get the inline listener method name for the given event name.
     */
    public static function for(string $eventName): string
    {
        return Str::camel(Str::slug($eventName)).'Handler';
    }
}
