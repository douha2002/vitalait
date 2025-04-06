<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockAlert;
use Phpml\Regression\LeastSquares;
use Illuminate\Support\Facades\DB;

class CheckStockLevels extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Check stock levels and send alerts for low quantities';

    public function handle()
    {
        // Get low stock items (quantity <= 2)
        $lowStockItems = Stock::where('quantite', '<=', 2)->get();

        if ($lowStockItems->isEmpty()) {
            $this->info('No low-stock items found.');
            return;
        }

        // Get users who should receive alerts
        $users = User::where('receive_alerts', true)->get();

        if ($users->isEmpty()) {
            $this->warn('No users found to receive alerts');
            return;
        }

        // Send email to each user
        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new LowStockAlert($lowStockItems));
                $this->info("Email sent to {$user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$user->email}: {$e->getMessage()}");
            }
        }
    }
    
}