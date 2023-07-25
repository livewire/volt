<div>
    <span>{{ $this->computedValue }}.</span>
</div>

<?php

use function Livewire\Volt\{computed};

$computedValue = computed(fn () => 'Computed value');
