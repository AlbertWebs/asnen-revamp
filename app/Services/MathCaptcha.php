<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class MathCaptcha
{
    public function issue(): array
    {
        $a = random_int(2, 12);
        $b = random_int(1, 10);
        $useSubtract = random_int(0, 1) === 1 && $a > $b;

        if ($useSubtract) {
            $question = "{$a} − {$b}";
            $answer = $a - $b;
        } else {
            $question = "{$a} + {$b}";
            $answer = $a + $b;
        }

        $token = Crypt::encryptString(json_encode([
            'answer' => $answer,
            'exp' => now()->addMinutes(10)->getTimestamp(),
        ], JSON_THROW_ON_ERROR));

        return [
            'question' => $question,
            'token' => $token,
        ];
    }

    public function check(?string $token, mixed $answer): bool
    {
        if (! is_string($token) || $token === '' || $answer === null || $answer === '') {
            return false;
        }

        if (! is_numeric($answer)) {
            return false;
        }

        try {
            $payload = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
        } catch (DecryptException|\JsonException $e) {
            Log::debug('Math captcha token rejected.', ['error' => $e->getMessage()]);

            return false;
        }

        if (! is_array($payload) || ! isset($payload['answer'], $payload['exp'])) {
            return false;
        }

        if ((int) $payload['exp'] < now()->getTimestamp()) {
            return false;
        }

        return (int) $payload['answer'] === (int) $answer;
    }

    /**
     * Build a valid token for automated tests.
     */
    public function tokenForAnswer(int $answer, int $ttlMinutes = 10): string
    {
        return Crypt::encryptString(json_encode([
            'answer' => $answer,
            'exp' => now()->addMinutes($ttlMinutes)->getTimestamp(),
        ], JSON_THROW_ON_ERROR));
    }
}
