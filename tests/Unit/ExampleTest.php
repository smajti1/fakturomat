<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest(): void
    {
        /** @phpstan-ignore method.alreadyNarrowedType */
        $this->assertTrue(true);
    }
}
