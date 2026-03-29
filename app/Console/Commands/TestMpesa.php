<?php

namespace App\Console\Commands;

use App\Services\MpesaService;
use Illuminate\Console\Command;

class TestMpesa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpesa:test {phone? : The phone number to send the STK push to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tests the M-Pesa STK push API by pinging a 1 KES prompt to the given phone number.';

    /**
     * Execute the console command.
     */
    public function handle(MpesaService $mpesa)
    {
        $phone = $this->argument('phone');

        if (! $phone) {
            $phone = $this->ask('Enter the Safaricom phone number to test (e.g. 2547XXXXXXXX)');
        }

        $this->info("Initiating 1 KES STK Push to $phone...");

        $response = $mpesa->stkPush(
            phoneNumber: $phone,
            amount: 1,
            reference: 'SAFARI_TEST',
            description: 'STK Integrity Test'
        );

        if (isset($response['ResponseCode']) && $response['ResponseCode'] == '0') {
            $this->info('✅ STK Push successfully dispatched to Safaricom! Check your phone.');
            $this->line('CheckoutRequestID: '.$response['CheckoutRequestID']);
        } else {
            $this->error('❌ STK Push failed!');
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
        }
    }
}
