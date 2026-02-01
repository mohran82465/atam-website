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
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;

/**
 * @property Form $form
 */
class RequestPasswordReset extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected static string $view = 'filament-panels::pages.auth.password-reset.request-password-reset';

    public ?array $data = [];

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    public function request(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return;
        }

        $data = $this->form->getState();
        $email = $data['email'];

        $otpService = app(OtpService::class);

        try {
            $sent = $otpService->sendPasswordResetOtp($email);
        } catch (\Throwable $e) {
            $message = config('app.debug')
                ? $e->getMessage()
                : 'Failed to send OTP. Please try again later. Check storage/logs/laravel.log for details.';

            Notification::make()
                ->title($message)
                ->danger()
                ->send();

            return;
        }

        if (! $sent) {
            $message = $otpService->getLastError()
                ?? 'We could not find a user with that email address or the user does not have admin access.';

            Notification::make()
                ->title($message)
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('OTP sent!')
            ->body('Check your email for the OTP code. Use it to reset your password.')
            ->success()
            ->send();

        $resetUrl = str_replace('/request', '/reset', Filament::getRequestPasswordResetUrl()) . '?email=' . urlencode($email);
        $this->redirect($resetUrl);
    }

    protected function getRateLimitedNotification(TooManyRequestsException $exception): ?Notification
    {
        return Notification::make()
            ->title('Too many attempts. Please try again in ' . $exception->secondsUntilAvailable . ' seconds.')
            ->danger();
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email address')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    public function loginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('Back to login')
            ->icon(match (__('filament-panels::layout.direction')) {
                'rtl' => FilamentIcon::resolve('panels::pages.password-reset.request-password-reset.actions.login.rtl') ?? 'heroicon-m-arrow-right',
                default => FilamentIcon::resolve('panels::pages.password-reset.request-password-reset.actions.login') ?? 'heroicon-m-arrow-left',
            })
            ->url(filament()->getLoginUrl());
    }

    public function getTitle(): string | Htmlable
    {
        return 'Forgot password?';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Request OTP';
    }

    protected function getFormActions(): array
    {
        return [
            $this->getRequestFormAction(),
        ];
    }

    protected function getRequestFormAction(): Action
    {
        return Action::make('request')
            ->label('Send OTP')
            ->submit('request');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
