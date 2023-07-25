<?php

use function Livewire\Volt\{state};

// state(['name' => 'World']);

?>

<div>
    @volt('named-lazy-on-load-fragment-component', ['lazy' => true, 'on-load' => true])
    <div>
        Hello From Named Lazy On Load
    </div>
    @endvolt

    @volt('named-lazy-fragment-component', lazy: true)
    <div>
        Hello From Named Lazy
    </div>
    @endvolt

    @volt(['lazy' => true, 'on-load' => true])
    <div>
        Hello From Lazy On Load
    </div>
    @endvolt

    @volt(lazy: true)
    <div>
        Hello From Lazy
    </div>
    @endvolt

    @volt
    <div>
        Hello From Eager
    </div>
    @endvolt

    @volt(lazy: false)
    <div>
        Hello From Non Named Eager
    </div>
    @endvolt

    @volt('named-eager-fragment-component')
    <div>
        Hello From Named Eager
    </div>
    @endvolt
</div>
