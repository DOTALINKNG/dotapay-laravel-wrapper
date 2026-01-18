<?php

namespace DotaPay\LaravelSdk\Data\Payment;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;
use DotaPay\LaravelSdk\Data\Customers\Transaction;

/**
 * Payment response wrapping transaction, wallet, and wallet transactions.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class PaymentResponse implements ArrayAccess
{
    use ArrayAccessible;

    /**
     * @param array<Transaction> $walletTransactions
     */
    public function __construct(
        public ?PaymentTransaction $transaction,
        public ?Wallet $wallet = null,
        public array $walletTransactions = [],
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        // Handle 'data' wrapper
        $responseData = $data['data'] ?? $data;

        $transaction = null;
        if (isset($responseData['transaction']) && is_array($responseData['transaction'])) {
            $transaction = PaymentTransaction::fromArray($responseData['transaction']);
        } elseif (isset($responseData['id'])) {
            // Transaction data is at the root level
            $transaction = PaymentTransaction::fromArray($responseData);
        }

        $wallet = null;
        if (isset($responseData['wallet']) && is_array($responseData['wallet'])) {
            $wallet = Wallet::fromArray($responseData['wallet']);
        }

        $walletTransactions = [];
        if (isset($responseData['wallet_transactions']) && is_array($responseData['wallet_transactions'])) {
            $walletTransactions = array_map(
                fn (array $tx) => Transaction::fromArray($tx),
                $responseData['wallet_transactions']
            );
        }

        return new self(
            transaction: $transaction,
            wallet: $wallet,
            walletTransactions: $walletTransactions,
        );
    }
}
