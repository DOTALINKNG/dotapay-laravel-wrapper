<?php

namespace DotaPay\LaravelSdk\Http\Clients;

use DotaPay\LaravelSdk\DotapayManager;
use DotaPay\LaravelSdk\Exceptions\DotapayRequestException;
use Illuminate\Http\Client\Response;

abstract class BaseClient
{
    protected DotapayManager $manager;

    public function __construct(DotapayManager $manager)
    {
        $this->manager = $manager;
    }

    protected function get(string $path, array $query = []): array
    {
        $response = $this->manager->http()->get($this->manager->path($path), $query);
        return $this->handle($response);
    }

    protected function post(string $path, array $body = []): array
    {
        $response = $this->manager->http()->post($this->manager->path($path), $body);
        return $this->handle($response);
    }

    protected function put(string $path, array $body = []): array
    {
        $response = $this->manager->http()->put($this->manager->path($path), $body);
        return $this->handle($response);
    }

    protected function patch(string $path, array $body = []): array
    {
        $response = $this->manager->http()->patch($this->manager->path($path), $body);
        return $this->handle($response);
    }

    protected function delete(string $path, array $query = []): array
    {
        $response = $this->manager->http()->delete($this->manager->path($path), $query);
        return $this->handle($response);
    }

    protected function handle(Response $response): array
    {
        $data = null;
        try {
            $data = $response->json();
        } catch (\Throwable) {
            $data = null;
        }

        if ($response->successful()) {
            return is_array($data) ? $data : ['data' => $data];
        }

        $throw = (bool)($this->manager->config()['throw'] ?? true);
        if ($throw) {
            $message = $this->bestErrorMessage($data) ?? ($response->reason() ?: 'DotaPay request failed');
            throw new DotapayRequestException($response->status(), is_array($data) ? $data : null, $message);
        }

        return is_array($data) ? $data : ['data' => $data, 'status' => $response->status()];
    }

    protected function bestErrorMessage(?array $data): ?string
    {
        if (!is_array($data)) {
            return null;
        }

        // Common Laravel API patterns
        if (!empty($data['message']) && is_string($data['message'])) {
            return $data['message'];
        }
        if (!empty($data['error']) && is_string($data['error'])) {
            return $data['error'];
        }
        if (!empty($data['errors']) && is_array($data['errors'])) {
            $first = collect($data['errors'])->flatten()->first();
            if (is_string($first)) {
                return $first;
            }
        }

        return null;
    }
}
