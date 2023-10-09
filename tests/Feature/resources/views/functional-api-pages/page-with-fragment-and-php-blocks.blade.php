<div>
    @volt('fragment-component-with-php-blocks')
    <div>
        @php
            $name = 'Nuno';
        @endphp

        Hello {{ $name }}
    </div>
    @endvolt
</div>
