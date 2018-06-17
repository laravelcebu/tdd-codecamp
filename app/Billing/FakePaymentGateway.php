<?php

namespace App\Billing;

class FakePaymentGateway
{
	protected $charges;

    public function __construct()
    {
        $this->charges = collect();
    }

    public function getValidTestToken()
    {
        return 'valid-token';
    }

    public function totalCharges()
    {
        return $this->charges->sum();
    }
}