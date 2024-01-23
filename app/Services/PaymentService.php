<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Round;
use App\Traits\crud;

class PaymentService extends BaseService
{
    use crud;

    public function model()
    {
        return Payment::class;
    }

    public function createPayment($request)
    {
        $payment = $this->store($request);

        if ($payment->status == "successful") {
            Round::query()->create([
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'result' => 'no_result',
                'play_status' => 'not_played',
                'gift_status' => 'unavailable'
            ]);
        }

        return $payment;
    }
}
