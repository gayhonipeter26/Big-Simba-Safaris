<?php

namespace App\Console\Commands;

use App\Services\MpesaService;
use Illuminate\Console\Command;

class MpesaRegisterUrls extends Command
{
    /**
     * The name and signature of the console command mission registry.
     *
     * @var string
     */
    protected $signature = 'mpesa:register-urls {shortcode?}';

    /**
     * The console command description protocol.
     *
     * @var string
     */
    protected $description = 'Register M-Pesa C2B Confirmation and Validation URLs.';

    /**
     * Execute the console command mission objective.
     */
    public function handle(MpesaService $mpesaService)
    {
        $shortcode = $this->argument('shortcode') ?? config('services.mpesa.shortcode');

        if (! $shortcode) {
            $this->error('No M-Pesa Shortcode provided. Check .env or pass as argument.');

            return 1;
        }

        $appUrl = config('app.url');

        if (str_contains($appUrl, 'localhost')) {
            $this->warn('Warning: APP_URL is pointing to localhost. M-Pesa callbacks require a public URL (e.g. Ngrok).');
        }

        $validationUrl = "{$appUrl}/api/mpesa/c2b/validation";
        $confirmationUrl = "{$appUrl}/api/mpesa/c2b/confirmation";

        $this->info("Registering URLs for Shortcode: {$shortcode}");
        $this->info("Validation URL: {$validationUrl}");
        $this->info("Confirmation URL: {$confirmationUrl}");

        $response = $mpesaService->registerUrls($shortcode, $validationUrl, $confirmationUrl);

        if (isset($response['OriginatorCoversationID'])) {
            $this->info('Registration Successful!');
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
        } else {
            $this->error('Registration Failed!');
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
        }

        return 0;
    }
}
