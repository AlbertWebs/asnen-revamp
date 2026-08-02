<?php

namespace App\Services\Donations;

class NullPaymentGateway implements PaymentGatewayInterface
{
    public function initiate(array $payload): array
    {
        return [
            'redirect_url' => null,
            'reference' => null,
        ];
    }

    public function isConfigured(): bool
    {
        return false;
    }
}
