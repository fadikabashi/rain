<?php

namespace App\Console\Commands;

use App\Services\AbandonedCartService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAbandonedCartReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carts:send-reminders {--hours=24 : Hours since cart was abandoned}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for abandoned carts';

    /**
     * Execute the console command.
     */
    public function handle(AbandonedCartService $abandonedCartService)
    {
        $hours = (int) $this->option('hours');
        $carts = $abandonedCartService->getCartsForReminder($hours);

        $this->info("Found {$carts->count()} abandoned carts to remind.");

        $sent = 0;
        foreach ($carts as $cart) {
            if ($cart->email) {
                // TODO: Send email reminder
                // Mail::to($cart->email)->send(new AbandonedCartReminderMail($cart));
                
                $cart->incrementReminder();
                $sent++;
                $this->line("Reminder sent to: {$cart->email}");
            }
        }

        $this->info("Sent {$sent} reminders.");
        return Command::SUCCESS;
    }
}
