<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $lowStockItems;

    public function __construct($lowStockItems)
    {
        $this->lowStockItems = $lowStockItems;
    }

    public function build()
    {
        return $this->subject('Alerte: Stock Faible - Demande d\'Achat')
                    ->view('emails.stock_alert');
    }
}