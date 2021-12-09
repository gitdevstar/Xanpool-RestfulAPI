<?php

namespace App\Http\Controllers\Xanpool\Models;

use App\Http\Controllers\Xanpool\Xanpool;

class Transaction
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
     * get()
     *
     * @return array
     */
    public function get($id)
    {
        return $this->xanpool->request('transaction',['id'=>$id], [], 'GET')->results();
    }

    /**
     * estimateCost()
     * @param $type = ['buy', 'sell']
     * @param $methods = ['paynow', 'fps', 'prompt-pay', 'upi', 'duit-now', 'nz-bank-transfer', 'gojek-id', 'instapay', 'pay-id', 'viettel-pay']
     *
     * @return array ['crypto', 'fiat', 'serviceCharge', 'total', 'currency', 'cryptoPrice', 'cryptoPriceUsd']
     */
    public function estimateCost($data)
    {
        return $this->xanpool->request('estimate_transactions_cost',[], $data, 'GET')->results();
    }

    /**
     * create()
     * @param $type = ['buy', 'sell']
     * @return array
     */
    public function create($id, $type = 'buy', $data)
    {
        return $this->xanpool->request('transaction_create', ['userId' => $id], array_merge(['type' => $type], $data),'POST')->results();
    }

    /**
     * getFilterAll()
     * @param $status = ['pending', 'completed', 'fiat_received', 'btc_in_mempool', 'btc_received', 'expired_fiat_not_received', 'expired_btc_not_in_mempool', 'payout_failed', 'refunded', 'cancelled']
     * @return array
     */
    public function getFilterAll($status = 'pending', $page = 0, $limit = 50, $from = null, $to = null)
    {
        return $this->xanpool->request('transactions_v2', [], [
            'status' => $status,
            'page' => $page,
            'pageSize' => $limit,
            'from' => $from,
            'to' => $to,
        ], 'GET', false)->results();
    }

    /**
     * getAll()
     *
     * @return array
     */
    public function getAll()
    {
        return $this->xanpool->request('transactions',[], [], false)->results();
    }

    /**
     * cancel()
     *
     * @return array
     */
    public function cancel($id, $txnId)
    {
        return $this->xanpool->request('transaction_cancel',['userId'=>$id, 'transactionId' => $txnId], [], 'POST')->results();
    }
}
