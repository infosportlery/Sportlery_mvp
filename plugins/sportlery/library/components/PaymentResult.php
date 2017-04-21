<?php

namespace Sportlery\Library\Components;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Mollie;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Str;
use RainLab\Translate\Classes\Translator;
use Sportlery\Library\Classes\EventJoinStatus;
use Sportlery\Library\Models\Event;
use Sportlery\Library\Models\Payment;

class PaymentResult extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Payment result',
            'description' => 'Show the result of a payment',
        ];
    }

    public function init()
    {
        $this->addComponent('RainLab\User\Components\Session', 'session', [
            'security' => 'user',
            'redirect' => 'login',
        ]);
    }

    public function onRun()
    {
        $payment = $this->page['user']->payments()->where('slug', $this->param('slug'))->first();
        $this->page['payment'] = $payment;

        if ($payment) {
            try {
                /** @var \Mollie\Laravel\Wrappers\MollieApiWrapper $api */
                $api = Mollie::api();
                $molliePayment = $api->payments()->get($payment->transaction_reference);
            } catch (\Mollie_API_Exception $e) {
                $this->page['payment'] = null;
                return;
            }

            $user = $this->page['user'];
            $event = Event::find($molliePayment->metadata->event_id);
            $previousStatus = $payment->status;
            $payment->status = $molliePayment->status;
            $this->page['redirectUrl'] = $this->controller->pageUrl('events-profile', ['id' => $event->getHashId()], false);
            $this->page['paymentUrl'] = $molliePayment->getPaymentUrl();

            if ($previousStatus !== 'paid' && $molliePayment->isPaid()) {
                $payment->paid_at = new Carbon($molliePayment->paidDatetime);
                $user->events()->sync([$event->id => [
                    'payment_id' => $payment->id,
                    'status' => EventJoinStatus::GOING,
                ]], false);
                $event->increment('current_attendees');
            } elseif (!$molliePayment->isPaid()) {
                $payment->paid_at = null;
            }

            $payment->save();
        }
    }
}
