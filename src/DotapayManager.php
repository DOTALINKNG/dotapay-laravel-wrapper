<?php

namespace DotaPay\LaravelSdk;

use DotaPay\LaravelSdk\Exceptions\DotapayConfigException;
use DotaPay\LaravelSdk\Http\Clients\CustomersClient;
use DotaPay\LaravelSdk\Http\Clients\PaymentClient;
use DotaPay\LaravelSdk\Http\Clients\SettlementsClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DotapayManager
{
    /** @var array<string,mixed> */
    private array $config;

    private ?PendingRequest $http = null;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Use a different private key at runtime (useful for multi-merchant platforms).
     */
    public function usingPrivateKey(string $privateKey): self
    {
        $clone = clone $this;
        $clone->config['private_key'] = $privateKey;
        $clone->http = null;
        return $clone;
    }

    public function customers(): CustomersClient
    {
        return new CustomersClient($this);
    }

    public function payment(): PaymentClient
    {
        return new PaymentClient($this);
    }

    public function settlements(): SettlementsClient
    {
        return new SettlementsClient($this);
    }

    public function http(): PendingRequest
    {
        if ($this->http) {
            return $this->http;
        }

        $baseUrl = rtrim((string)($this->config['base_url'] ?? ''), '/');
        $prefix = trim((string)($this->config['api_prefix'] ?? 'api/v1'), '/');

        if ($baseUrl === '') {
            throw new DotapayConfigException('dotapay.base_url is required');
        }

        $privateKey = (string)($this->config['private_key'] ?? '');
        if ($privateKey === '') {
            throw new DotapayConfigException('dotapay.private_key is required (set DOTAPAY_PRIVATE_KEY)');
        }

        $header = (string)($this->config['private_key_header'] ?? 'DPPRIVATEKEY');

        $timeout = (float)($this->config['timeout'] ?? 20);
        $connectTimeout = (float)($this->config['connect_timeout'] ?? 10);
        $retries = (int)($this->config['retries'] ?? 2);
        $retrySleepMs = (int)($this->config['retry_sleep_ms'] ?? 300);
        $userAgent = (string)($this->config['user_agent'] ?? 'dotapay-laravel-sdk/1.0');

        $request = Http::baseUrl($baseUrl . '/' . $prefix)
            ->acceptJson()
            ->asJson()
            ->timeout($timeout)
            ->connectTimeout($connectTimeout)
            ->withHeaders([
                $header => $privateKey,
                'User-Agent' => $userAgent,
            ])
            ->retry($retries, $retrySleepMs, function ($exception, $request) {
                // Let Laravel retry on connection exceptions automatically.
                return true;
            }, throw: false);

        $this->http = $request;

        return $this->http;
    }

    /**
     * Build a full path under the configured prefix.
     */
    public function path(string $path): string
    {
        return ltrim($path, '/');
    }

    /** @return array<string,mixed> */
    public function config(): array
    {
        return $this->config;
    }
}
