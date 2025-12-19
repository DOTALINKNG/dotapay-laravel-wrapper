<?php

namespace DotaPay\LaravelSdk\Tests;

use DotaPay\LaravelSdk\DotaPayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [DotaPayServiceProvider::class];
    }
}
