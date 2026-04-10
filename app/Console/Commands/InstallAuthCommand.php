<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallAuthCommand extends Command
{
    protected $signature = 'auth:install {--force : Overwrite existing files}';

    protected $description = 'Install the auth module skeleton from blueprints';

    public function handle(): int
    {
        $this->info('Installing Auth module...');

        $this->configureFeatures();

        $domainNamespace = 'Domain\\Auth';
        $domainPath = base_path('domain/Auth');

        $stubs = [
            'blueprints/Auth/Actions/LoginAction.php.stub' => $domainPath . '/Actions/LoginAction.php',
            'blueprints/Auth/Actions/LogoutAction.php.stub' => $domainPath . '/Actions/LogoutAction.php',
            'blueprints/Auth/Actions/RegisterAction.php.stub' => $domainPath . '/Actions/RegisterAction.php',
            'blueprints/Auth/Actions/ForgotPasswordAction.php.stub' => $domainPath . '/Actions/ForgotPasswordAction.php',
            'blueprints/Auth/Actions/ResetPasswordAction.php.stub' => $domainPath . '/Actions/ResetPasswordAction.php',
            'blueprints/Auth/Actions/VerifyOtpAction.php.stub' => $domainPath . '/Actions/VerifyOtpAction.php',
            'blueprints/Auth/Data/LoginData.php.stub' => $domainPath . '/Data/LoginData.php',
            'blueprints/Auth/Data/RegisterData.php.stub' => $domainPath . '/Data/RegisterData.php',
            'blueprints/Auth/Data/ResetPasswordData.php.stub' => $domainPath . '/Data/ResetPasswordData.php',
            'blueprints/Auth/Data/OtpData.php.stub' => $domainPath . '/Data/OtpData.php',
            'blueprints/Auth/Data/AuthResponseData.php.stub' => $domainPath . '/Data/AuthResponseData.php',
            'blueprints/Auth/Notifications/OtpNotification.php.stub' => $domainPath . '/Notifications/OtpNotification.php',
            'blueprints/Auth/Exceptions/InvalidCredentialsException.php.stub' => $domainPath . '/Exceptions/InvalidCredentialsException.php',
            'blueprints/Auth/Exceptions/InvalidTokenException.php.stub' => $domainPath . '/Exceptions/InvalidTokenException.php',
            'blueprints/Auth/Exceptions/InvalidOtpException.php.stub' => $domainPath . '/Exceptions/InvalidOtpException.php',
            'blueprints/Auth/Controllers/LoginController.php.stub' => app_path('Http/Api/Auth/Controllers/LoginController.php'),
            'blueprints/Auth/Controllers/RegisterController.php.stub' => app_path('Http/Api/Auth/Controllers/RegisterController.php'),
            'blueprints/Auth/Controllers/ForgotPasswordController.php.stub' => app_path('Http/Api/Auth/Controllers/ForgotPasswordController.php'),
            'blueprints/Auth/Controllers/ResetPasswordController.php.stub' => app_path('Http/Api/Auth/Controllers/ResetPasswordController.php'),
            'blueprints/Auth/Controllers/ProfileController.php.stub' => app_path('Http/Api/Auth/Controllers/ProfileController.php'),
            'blueprints/Auth/Controllers/EmailVerificationController.php.stub' => app_path('Http/Api/Auth/Controllers/EmailVerificationController.php'),
            'blueprints/Auth/Controllers/ResendVerificationNotificationController.php.stub' => app_path('Http/Api/Auth/Controllers/ResendVerificationNotificationController.php'),
            'blueprints/Auth/Controllers/OtpVerificationController.php.stub' => app_path('Http/Api/Auth/Controllers/OtpVerificationController.php'),
            'blueprints/Auth/Requests/LoginRequest.php.stub' => app_path('Http/Api/Auth/Requests/LoginRequest.php'),
            'blueprints/Auth/Requests/RegisterRequest.php.stub' => app_path('Http/Api/Auth/Requests/RegisterRequest.php'),
            'blueprints/Auth/Requests/ForgotPasswordRequest.php.stub' => app_path('Http/Api/Auth/Requests/ForgotPasswordRequest.php'),
            'blueprints/Auth/Requests/ResetPasswordRequest.php.stub' => app_path('Http/Api/Auth/Requests/ResetPasswordRequest.php'),
            'blueprints/Auth/Requests/EmailVerificationRequest.php.stub' => app_path('Http/Api/Auth/Requests/EmailVerificationRequest.php'),
            'blueprints/Auth/Requests/VerifyOtpRequest.php.stub' => app_path('Http/Api/Auth/Requests/VerifyOtpRequest.php'),
            'blueprints/Auth/config/auth_features.php.stub' => config_path('auth_features.php'),
        ];

        foreach ($stubs as $stub => $destination) {
            $this->publishFile($stub, $destination, ['{{namespace}}' => $domainNamespace]);
        }

        $this->appendRoutes();

        $this->info('Auth module installed successfully!');

        return self::SUCCESS;
    }

    protected function publishFile(string $stub, string $destination, array $replacements = []): void
    {
        if (File::exists($destination) && ! $this->option('force')) {
            $this->warn("File already exists: {$destination}. Use --force to overwrite.");

            return;
        }

        $content = File::get(base_path($stub));

        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        File::ensureDirectoryExists(dirname($destination));
        File::put($destination, $content);

        $this->line("Published: {$destination}");
    }

    protected function appendRoutes(): void
    {
        $apiRoutesPath = base_path('routes/api.php');
        $stubRoutesPath = base_path('blueprints/Auth/routes/api.php.stub');

        if (! File::exists($apiRoutesPath) || ! File::exists($stubRoutesPath)) {
            return;
        }

        $stubRoutes = File::get($stubRoutesPath);

        if (Str::contains(File::get($apiRoutesPath), 'LoginController')) {
            $this->warn('Routes already contain LoginController. Skipping route appending.');

            return;
        }

        File::append($apiRoutesPath, PHP_EOL . $stubRoutes);
        $this->line('Appended routes to routes/api.php');
    }

    protected function configureFeatures(): void
    {
        $registration = $this->confirm('Enable registration?', config('auth_features.registration', true));
        $emailVerification = $this->confirm('Enable email verification?', config('auth_features.email_verification', false));
        $emailOtp = $this->confirm('Enable email OTP?', config('auth_features.email_otp', false));
        $otpExpiry = config('auth_features.otp_expiry', 10);

        if ($emailOtp) {
            $otpExpiry = $this->ask('OTP expiry time in minutes?', config('auth_features.otp_expiry', 10));
        }

        $this->updateConfig([
            'registration' => $registration,
            'email_verification' => $emailVerification,
            'email_otp' => $emailOtp,
            'otp_expiry' => (int) $otpExpiry,
        ]);

        config([
            'auth_features.registration' => $registration,
            'auth_features.email_verification' => $emailVerification,
            'auth_features.email_otp' => $emailOtp,
            'auth_features.otp_expiry' => $otpExpiry,
        ]);
    }

    protected function updateConfig(array $data): void
    {
        $configPath = config_path('auth_features.php');

        if (! File::exists($configPath)) {
            $stubPath = base_path('blueprints/Auth/config/auth_features.php.stub');
            if (File::exists($stubPath)) {
                File::copy($stubPath, $configPath);
            } else {
                return;
            }
        }

        $content = File::get($configPath);

        foreach ($data as $key => $value) {
            $exportValue = var_export($value, true);
            $content = preg_replace("/'{$key}' => .*,/", "'{$key}' => {$exportValue},", $content);
        }

        File::put($configPath, $content);
    }
}
