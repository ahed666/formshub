<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
class InvoicePaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct($invoice)
    {
        Log::info('construct:',[$invoice]);
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info('tomail:',[$this->invoice]);
        return (new MailMessage)
            ->subject('Invoice Paid')
            ->greeting('Thank you for your payment!')
            ->line('We have successfully received your payment. Here are the details:')
            ->line($this->invoice->invoice_pdf)
            ->line("**Invoice ID:** {$this->invoice->id}")
            ->line("**Amount Paid:** " . number_format($this->invoice->amount_paid / 100, 2) . " " . strtoupper($this->invoice->currency))
            ->action('Download Invoice', $this->invoice->invoice_pdf)
            ->line('If you have any questions, please contact us at info@formshub.net.');
            // ->markdown('emails.invoice_paid', ['invoice' => $this->invoice]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'amount_paid' => $this->invoice->amount_paid,
            'currency' => $this->invoice->currency,
        ];
    }
}
