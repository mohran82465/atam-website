<?php

namespace App\Services;

use App\Models\PasswordResetOtp;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class OtpService
{
    public const OTP_EXPIRY_MINUTES = 15;

    public const OTP_LENGTH = 6;

    protected ?string $lastError = null;

    /**
     * Get the last error message (for debugging).
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Generate and send OTP to user's email for password reset.
     */
    public function sendPasswordResetOtp(string $email): bool
    {
        $this->lastError = null;

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->lastError = 'No user found with this email. Create the user first via: php artisan make:filament-user';

            return false;
        }

        if ($user instanceof FilamentUser && ! $user->canAccessPanel(Filament::getCurrentPanel())) {
            $this->lastError = 'User does not have admin panel access.';

            return false;
        }

        $otp = $this->generateOtp();
        $this->storeOtp($email, $otp);

        try {
            $this->sendOtpEmail($email, $otp);
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('OTP send failed', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }

        return true;
    }

    /**
     * Verify OTP and return true if valid.
     */
    public function verifyOtp(string $email, string $otp): bool
    {
        $record = PasswordResetOtp::where('email', $email)
            ->where('otp', $otp)
            ->first();

        if (! $record || $record->isExpired()) {
            return false;
        }

        $record->delete();

        return true;
    }

    protected function generateOtp(): string
    {
        return Str::padLeft((string) random_int(0, 999999), self::OTP_LENGTH, '0');
    }

    protected function storeOtp(string $email, string $otp): void
    {
        PasswordResetOtp::where('email', $email)->delete();

        PasswordResetOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
        ]);
    }

    protected function sendOtpEmail(string $email, string $otp): void
    {
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name', 'Atam Admin');

        if (empty($fromAddress)) {
            throw new \RuntimeException('MAIL_FROM_ADDRESS is not set in .env');
        }

        Mail::raw($this->getOtpEmailBody($otp), function ($message) use ($email, $fromAddress, $fromName) {
            $message->from($fromAddress, $fromName)
                ->to($email)
                ->subject('OTP for Atam super admin account');
        });
    }

    protected function getOtpEmailBody(string $otp): string
    {
        return <<<TEXT
        Your OTP for resetting your Atam admin password is: {$otp}

        This code will expire in 15 minutes.

        Please do not share this code with anyone.
        TEXT;
    }
}
