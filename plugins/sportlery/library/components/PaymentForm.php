<?php

namespace Sportlery\Library\Components;

use Auth;
use Illuminate\Support\Facades\Cache;
use Mollie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Str;
use RainLab\Translate\Classes\Translator;
use Sportlery\Library\Models\Event;
use Sportlery\Library\Models\Payment;

class PaymentForm extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Payment form',
            'description' => 'A generic payment form',
        ];
    }

    public function defineProperties()
    {
        return [
            'redirectPage' => [
                'title'       => 'Redirect page',
                'description' => 'The page to redirect to after the payment was made',
                'type'        => 'dropdown',
                'showExternalParam' => false,
            ],
        ];
    }

    public function getRedirectPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        /** @var \Mollie\Laravel\Wrappers\MollieApiWrapper $api */
        $api = Mollie::api();
        $this->page['paymentMethods'] = Cache::remember('mollie_payment_methods', 60, function () use ($api) {
            return collect($api->methods()->all());
        });
        $allIssuers = Cache::remember('mollie_issuers', 60, function () use ($api) {
            return collect($api->issuers()->all());
        });
        $allIssuers = $allIssuers->groupBy('method');
        $issuers = [];

        foreach ($allIssuers as $methodId => $methodIssuers) {
            $issuers[$methodId] = array_pluck($methodIssuers, 'name', 'id');
        }
        $this->page['issuers'] = $issuers;
    }

    public function onPay()
    {
        $event = Event::findByHashId($this->param('id'));
        $paymentMethod = post('payment_method');
        $issuerId = post('issuer_id');
        $paymentSlug = Str::random(30);
        $redirectUrl = $this->controller->pageUrl($this->property('redirectPage'), ['slug' => $paymentSlug], false);
        $user = Auth::getUser();

        try {
            /** @var \Mollie\Laravel\Wrappers\MollieApiWrapper $api */
            $api = Mollie::api();
            $molliePayment = $api->payments()->create([
                'amount' => $event->price + 0.35,
                'description' => 'Ticket '.$event->name,
                'redirectUrl' => $redirectUrl,
                'method' => $paymentMethod,
                'metadata' => [
                    'event_id' => $event->id,
                    'user_id' => $user->id ?? null,
                    'slug' => $paymentSlug,
                ],
                'issuer' => $issuerId,
            ]);
            $payment = Payment::fromMolliePayment($molliePayment);

            return Redirect::away($molliePayment->getPaymentUrl());
        } catch (\Mollie_API_Exception $e) {
            Log::error('Mollie payment failed with message ['.$e->getMessage().'] on field ['.$e->getField().']');

            return Redirect::back()->withError('Payment failed.');
        }
    }
}
