<?php

namespace App\Services\Donations;

interface PaymentGatewayInterface
{
    /**
     * @param  array<string, mixed>  $payload
     * @return array{redirect_url: string|null, reference: string|null}
     */
    public function initiate(array $payload): array;

    public function isConfigured(): bool;
}
