<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $content = 'Page Livewire Styles and Scripts';
}

?>

<html>
    <head>
        @livewireStyles
    </head>
    <body>
    @volt
        <div>
            <h1> {{ $content }}</h1>
        </div>
    @endvolt
    @livewireScripts
    </body>
</html>
