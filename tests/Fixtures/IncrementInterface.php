<?php

namespace Tests\Fixtures;

interface IncrementInterface
{
    public function increment();

    public function alsoIncrement(): void;

    public function alsoIncrementButReturnInt(): int;
}
