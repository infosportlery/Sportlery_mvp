<?php

namespace Sportlery\Library\Models;

use October\Rain\Database\Model;

class Payment extends Model
{
    protected $table = 'spr_payments';

    protected $dates = ['paid_at'];

    /**
     * @param \Mollie_API_Object_Payment $molliePayment
     * @return \Sportlery\Library\Models\Payment
     */
    public static function fromMolliePayment($molliePayment)
    {
        $payment = new Payment;

        $payment->slug = $molliePayment->metadata->slug;
        $payment->amount = $molliePayment->amount;
        $payment->status = $molliePayment->status;
        $payment->payment_method = $molliePayment->method;
        $payment->transaction_reference = $molliePayment->id;
        $payment->user_id = $molliePayment->metadata->user_id;

        return $payment->save() ? $payment : null;
    }
}
