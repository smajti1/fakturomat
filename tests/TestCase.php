<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

	protected function getBaseUrl(): string
	{
		return 'http://' . env('APP_URL');
	}
}
