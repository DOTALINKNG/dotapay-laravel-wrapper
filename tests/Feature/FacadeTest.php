<?php

namespace DotaPay\LaravelSdk\Tests\Feature;

use DotaPay\LaravelSdk\Tests\TestCase;

class FacadeTest extends TestCase
{
    public function test_it_registers_the_dotapay_singleton(): void
    {
        $this->assertTrue($this->app->bound('dotapay'));
        $this->assertTrue($this->app->bound(\DotaPay\LaravelSdk\DotapayManager::class));
    }
}
