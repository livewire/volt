<?php

use Livewire\Volt\Component;

use function Laravel\Folio\{render};

render(fn ($view) => $view->with('name', 'from view'));

new class extends Component
{
    public string $name = 'from component';
}

?>

<div>
    @volt
    <div>
        <span>Hello {{ $name }}.</span>
    </div>
    @endvolt
</div>
