<?php

declare(strict_types=1);

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Process a payment.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function pay(array $data): array;

    /**
     * Verify a payment.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function verify(array $data): array;
}
