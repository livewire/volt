<?php

namespace Tests\Fixtures;

trait GlobalTrait
{
    public $globalProperty;

    public function setGlobalProperty($value)
    {
        $this->globalProperty = $value;
    }
}
