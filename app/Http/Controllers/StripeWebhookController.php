<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Support\Facades\Log;
use App\Services\PlanService;
use App\Services\SubscriptionService;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;



class StripeWebhookController extends CashierWebhookController
{
    protected $planService;
    protected $subscriptionService;
    protected $cashierWebhook;



    public function __construct(PlanService $planService,SubscriptionService $subscriptionService)
    {
        parent::__construct();
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->cashierWebhook = new CashierWebhookController();

        $this->planService = $planService;
        $this->subscriptionService=$subscriptionService;
       

    }

    public function handleWebhook(Request $request)
    {

        $event = $this->constructEventFromRequest($request);
        Log::info('Received new webhook event from Stripe',[$event->type]);

        // Pass the event to Laravel Cashier's handler if it exists
        // if ($this->shouldHandleByCashier($event->type)) {
        //     parent::handleWebhook($request);
        // }
        Log::info('Received new webhook event from Stripe',[$event->type]);

       
        // Custom handling for events not managed by Cashier
        switch ($event->type) {
            case 'product.updated':
                $this->planService->updateProductAndPrice($event->data->object);
                break;
          
            case 'invoice.paid':
                Log::info('invoice paid',[$event->data->object]);
            $this->subscriptionService->handleInvoicePaid($event->data->object);
                break;
            case 'price.updated':
                $this->planService->updateProductAndPrice($event->data->object);
                break;
            case 'customer.subscription.trial_will_end':
                $this->subscriptionService->handleTrialWillEnd($event->data->object);
                break;
            case 'customer.subscription.free_trial_will_end':
                $this->subscriptionService->handleFreeTrialWillEnd($event->data->object);
                break;

            case 'customer.subscription.created':
                $this->subscriptionService->handleSubscriptionCreated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->subscriptionService->handleSubscriptionDeleted($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->subscriptionService->handleSubscriptionUpdated($event->data->object);
                break;

            default:
                return response('Event not handled', 400);
        }

        return response('Webhook received', 200);
    }
    protected function constructEventFromRequest(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            return \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed.',[$endpointSecret]);
            abort(400, 'Webhook signature verification failed.');
        }
    }
    protected function shouldHandleByCashier($eventType)
    {
        // Define event types Cashier should handle
        $cashierEvents = [
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted',
            'customer.updated',
            'customer.deleted',
            // Add more Cashier-supported events if necessary
        ];

        return in_array($eventType, $cashierEvents);
    }
}
