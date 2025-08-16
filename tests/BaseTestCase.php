<?php

namespace Dipesh79\LogArcLaravel\Tests;

use Dipesh79\LogArcLaravel\LogArcServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class BaseTestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LogArcServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

}
