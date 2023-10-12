<?php

namespace Wontonee\Placetopay\Payment;

use Webkul\Payment\Payment\Payment;

class Placetopay extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'placetopay';

    public function getRedirectUrl()
    {
        return route('placetopay.process');
        
    }
}