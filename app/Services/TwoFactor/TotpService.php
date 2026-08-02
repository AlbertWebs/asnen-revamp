<?php

namespace App\Services\TwoFactor;

use App\Models\User;

/**
 * TOTP two-factor authentication service stub.
 *
 * Full enrollment UI and challenge flows will be delivered via Laravel Fortify
 * in a later phase. This service defines the application boundary for
 * enable / confirm / disable operations and optional recovery codes on the user.
 */
class TotpService
{
    /**
     * Begin TOTP enrollment: generate a secret and return provisioning details.
     *
     * @return array{secret: string, qr_code_url: string}
     */
    public function enable(User $user): array
    {
        // Fortify will replace this with pragmarx/google2fa or equivalent.
        $secret = bin2hex(random_bytes(16));

        return [
            'secret' => $secret,
            'qr_code_url' => '', // Populated when Fortify integration is added.
        ];
    }

    /**
     * Confirm TOTP enrollment with a valid time-based one-time password.
     */
    public function confirm(User $user, string $code, string $secret): bool
    {
        // Stub: persist secret and confirmed timestamp when Fortify is wired.
        if ($code === '') {
            return false;
        }

        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(json_encode($this->generateRecoveryCodes())),
        ])->save();

        return true;
    }

    /**
     * Disable TOTP and clear stored secrets and recovery codes.
     */
    public function disable(User $user): void
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    /**
     * @return list<string>
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = bin2hex(random_bytes(5));
        }

        return $codes;
    }
}
