<?php

namespace App\Services;

use App\Notifications\TrialEndingNotification;
use App\Notifications\FreeTrialEndingNotification;
use App\Notifications\InvoicePaidNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class NotificationService
{
    public function sendTrialEndingNotification($user, $endDate)
    {
        $endDateFormatted = Carbon::createFromTimestamp($endDate)->toFormattedDateString();
        $user->notify(new TrialEndingNotification($user, $endDateFormatted));
    }

    public function sendFreeTrialEndingNotification($user, $endDate)
    {
        $endDateFormatted = Carbon::createFromTimestamp($endDate)->toFormattedDateString();
        $user->notify(new FreeTrialEndingNotification($user, $endDateFormatted));
    }

    public function sendInvoicePaidNotification($user, $invoice)
    {   
        Log::info('send invoice email:',[$invoice]);
        $user->notify(new InvoicePaidNotification( $invoice));
    }
}
