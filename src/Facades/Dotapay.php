<?php

namespace DotaPay\LaravelSdk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \DotaPay\LaravelSdk\Http\Clients\CustomersClient customers()
 * @method static \DotaPay\LaravelSdk\Http\Clients\PaymentClient payment()
 * @method static \DotaPay\LaravelSdk\Http\Clients\SettlementsClient settlements()
 * @method static \DotaPay\LaravelSdk\DotapayManager usingPrivateKey(string $privateKey)
 */
class Dotapay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'dotapay';
    }
}
