<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentCreateRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    public function createPayment(PaymentCreateRequest $request): PaymentResource
    {
        $payment = $this->paymentService->createPayment($request);

        return new PaymentResource($payment);

    }

}
