<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testBasicTest(): void
	{
        $response = $this->get($this->getBaseUrl());

        $response->assertStatus(200);
    }
}
