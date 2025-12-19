# DotaPay Laravel SDK

A small Laravel-friendly SDK for calling DotaPay APIs:
- `customers/*`
- `payment/*`
- `settlements/*`

Authentication is handled with a **Private Key** sent as a request header (default: `DPPRIVATEKEY`).

> This package is an SDK (HTTP client). It does **not** add routes to your application.

## Requirements
- PHP 8.1+
- Laravel 10 or 11

## Installation

```bash
composer require dotapay/laravel-sdk
```

Publish the config (optional):

```bash
php artisan vendor:publish --tag=dotapay-config
```

## Configuration

Add these to `.env`:

```dotenv
DOTAPAY_BASE_URL=https://YOUR-TENANT-DOMAIN.dotapay.ng
DOTAPAY_API_PREFIX=api/v1
DOTAPAY_PRIVATE_KEY=PRIV_DP_xxxxxx
DOTAPAY_PRIVATE_KEY_HEADER=DPPRIVATEKEY
```

## Usage

### Customers

```php
use DotaPay\LaravelSdk\Facades\Dotapay;

// Create (fails if reference exists)
$created = Dotapay::customers()->create([
  'first_name' => 'John',
  'last_name' => 'Doe',
  'bvn' => '12345678901',
  'dob' => '1998-03-17',
  'email' => 'john@example.com',
  'reference' => 'my-app-user-123',
  'type' => 'wallet',
]);

// Create-or-get (idempotent by reference)
$customer = Dotapay::customers()->createOrGetByReference([
  'first_name' => 'John',
  'last_name' => 'Doe',
  'bvn' => '12345678901',
  'dob' => '1998-03-17',
  'email' => 'john@example.com',
  'reference' => 'my-app-user-123',
  'type' => 'wallet',
]);

// Show by id/code/reference
$found = Dotapay::customers()->show('my-app-user-123');

// Balance
$balance = Dotapay::customers()->balance('my-app-user-123');
```

### Payment

```php
$txn = Dotapay::payment()->request([
  'public_key' => 'PUB_DP_xxxxx',
  'order_id' => 'ORDER-10001',
  'customer_email' => 'john@example.com',
  'customer_name' => 'John Doe',
  'items' => [
    ['id' => 'sku-1', 'name' => 'Item 1', 'unit_cost' => 1000, 'quantity' => 1],
  ],
]);

$status = Dotapay::payment()->status($txn['transaction']['data']['reference'] ?? 'REF_...');
```

### Settlements

```php
$list = Dotapay::settlements()->index(['per_page' => 20]);

$withdraw = Dotapay::settlements()->withdraw([
  'wallet_id' => 'WALLET_ABC_live',
  'amount' => 5000, // NGN
  'settlement_bank_id' => 'SETTBANK_...',
]);

$direct = Dotapay::settlements()->withdrawDirect([
  'wallet_id' => 'WALLET_ABC_live',
  'amount' => 5000, // NGN
  'bank_code' => '058',
  'account_number' => '0123456789',
  'reference' => 'WD-'.now()->timestamp,
]);
```

## Multi-merchant platforms

If your application holds multiple DotaPay private keys, you can swap the key per request:

```php
$dotapay = app('dotapay')->usingPrivateKey($businessPrivateKey);
$customer = $dotapay->customers()->show('my-ref');
```

## Error handling

By default, non-2xx responses throw `DotaPay\LaravelSdk\Exceptions\DotapayRequestException`.
Disable throws via:

```dotenv
DOTAPAY_THROW=false
```

## License
MIT
