<?php

namespace App\Http\Controllers\Xanpool\Models;

use App\Http\Controllers\Xanpool\Xanpool;

class Misc
{

    /**
     * Response array
     *
     * @var Xanpool
     */
    private $xanpool;

    /**
     *  __construct
     *
     */
    public function __construct(Xanpool $xanpool)
    {
        $this->xanpool = $xanpool;
    }

    /**
     * supportedCryptos()
     *
     * @return array
     */
    public function supportedCryptos()
    {
        return $this->xanpool->request('cryptos',[], [], 'GET', false)->results();
    }

    /**
     * limits()
     *
     * @return array
     */
    public function limits()
    {
        return $this->xanpool->request('limits',[], [], 'GET', false)->results();
    }

    /**
     * prices()
     * @param $type = ['sell', 'buy']
     * @param list $currencies List of local currencies separated by comma
     * @param list $cryptoCurrencies List of local cryptoCurrencies separated by comma
     * @return array
     */
    public function prices($type = 'sell', $currencies, $cryptoCurrencies)
    {
        return $this->xanpool->request('prices', [], [
            'type' => $type,
            'currencies' => $currencies,
            'cryptoCurrencies' => $cryptoCurrencies,
        ], 'GET', false)->results();
    }

}
