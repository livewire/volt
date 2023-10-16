<?php

use Tests\Fixtures\Status;

?>

<div>
    Out fragment: {{ Status::DRAFT }}.

    @volt('fragment-component-using-imports-on-template')
        <div>
            In fragment: {{ Status::PUBLISHED }}.
        </div>
    @endvolt
</div>
