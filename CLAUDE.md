# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

DotaPay Laravel SDK - A Laravel HTTP client wrapper for DotaPay APIs (customers, payment, settlements). This is a library package, not an application. Authentication uses a private key sent as an HTTP header.

## Commands

```bash
# Install dependencies
composer install

# Run all tests
vendor/bin/phpunit

# Run a specific test
vendor/bin/phpunit --filter="TestName"

# Run tests in a specific file
vendor/bin/phpunit tests/Feature/FacadeTest.php
```

## Architecture

**Pattern**: Service Provider → Manager → Client

```
DotaPayServiceProvider    Registers DotapayManager singleton, publishes config
        ↓
DotapayManager            Central orchestrator; creates HTTP client, provides client factories
        ↓
BaseClient (abstract)     HTTP abstraction (get/post/put/patch/delete), error handling
        ↓
├── CustomersClient       /customers/* endpoints
├── PaymentClient         /payment/* endpoints
└── SettlementsClient     /settlements/* endpoints
```

**Entry Points**:
- Facade: `Dotapay::customers()`, `Dotapay::payment()`, `Dotapay::settlements()`
- Container: `app('dotapay')->customers()`
- Multi-tenant: `app('dotapay')->usingPrivateKey($key)->customers()`

**Key Files**:
- `src/DotapayManager.php` - HTTP client setup, lazy initialization, private key injection
- `src/Http/Clients/BaseClient.php` - Template for all API clients, response/error parsing
- `config/dotapay.php` - All configuration options with defaults

**Exception Handling**:
- `DotapayRequestException` - HTTP/API errors (has status code, response body, message)
- `DotapayConfigException` - Missing/invalid configuration

## Testing

Uses Orchestra Testbench for Laravel package testing. Tests are in `tests/` with `DotaPay\LaravelSdk\Tests\` namespace.
