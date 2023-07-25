<?php

namespace Tests\Fixtures;

trait IncrementTrait
{
    public int $counter = 0;

    public function increment(): void
    {
        $this->counter++;
    }
}
