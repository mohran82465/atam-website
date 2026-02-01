<?php

namespace App\Filament\Pages\Auth\PasswordReset;

use App\Services\OtpService;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\PasswordResetResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Locked;

/**
 * @property Form $form
 */
class ResetPassword extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected static string $view = 'filament-panels::pages.auth.password-reset.reset-password';

    #[Locked]
    public ?string $email = null;

    public ?string $otp = '';

    public ?string $password = '';

    public ?string $passwordConfirmation = '';

    public function mount(?string $email = null): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->email = $email ?? request()->query('email');

        $this->form->fill([
            'email' => $this->email,
            'otp' => '',
            'password' => '',
            'passwordConfirmation' => '',
        ]);
    }

    public function resetPassword(): ?PasswordResetResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        $email = $data['email'] ?? $this->email;
        $otp = $data['otp'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($otp)) {
            Notification::make()
                ->title('Email and OTP are required.')
                ->danger()
                ->send();

            return null;
        }

        $user = \App\Models\User::where('email', $email)->first();

        if (! $user) {
            Notification::make()
                ->title('Invalid email or OTP.')
                ->danger()
                ->send();

            return null;
        }

        if ($user instanceof FilamentUser && ! $user->canAccessPanel(Filament::getCurrentPanel())) {
            Notification::make()
                ->title('This user does not have admin access.')
                ->danger()
                ->send();

            return null;
        }

        if (! app(OtpService::class)->verifyOtp($email, $otp)) {
            Notification::make()
                ->title('Invalid or expired OTP. Please request a new one.')
                ->danger()
                ->send();

            return null;
        }

        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        Notification::make()
            ->title('Password reset successfully!')
            ->success()
            ->send();

        return app(PasswordResetResponse::class);
    }

    protected function getRateLimitedNotification(TooManyRequestsException $exception): ?Notification
    {
        return Notification::make()
            ->title('Too many attempts. Please try again in ' . $exception->secondsUntilAvailable . ' seconds.')
            ->danger();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getOtpFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email address')
            ->email()
            ->required()
            ->autofocus();
    }

    protected function getOtpFormComponent(): Component
    {
        return TextInput::make('otp')
            ->label('OTP code')
            ->required()
            ->maxLength(6)
            ->numeric()
            ->placeholder('Enter the 6-digit code from your email');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('New password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(PasswordRule::default())
            ->same('passwordConfirmation')
            ->validationAttribute('password');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Confirm password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    public function getTitle(): string | Htmlable
    {
        return 'Reset password';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Reset your password';
    }

    protected function getFormActions(): array
    {
        return [
            $this->getResetPasswordFormAction(),
        ];
    }

    public function getResetPasswordFormAction(): Action
    {
        return Action::make('resetPassword')
            ->label('Reset password')
            ->submit('resetPassword');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
