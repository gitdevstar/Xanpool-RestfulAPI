<?php

namespace App\Http\Controllers\Xanpool;

use App\Http\Controllers\Xanpool\Request;
use App\Http\Controllers\Xanpool\Models\User;
use App\Http\Controllers\Xanpool\Models\Transaction;
use App\Http\Controllers\Xanpool\Models\Misc;

class Xanpool
{

    /**
     * API key
     *
     * @var string
     */
    private $key;

    /**
     * API secret
     *
     * @var string
     */
    private $secret;

    /**
     *
     * @var string
     */
    private $token;

    /**
     * API Real Path
     *
     * @var array
     */
    private $apiPath = 'https://xanpool.com';

    /**
     * API Paths
     *
     * @var array
     */
    private $paths = [
        "oauth2-token"                       => "/api/oauth2/user-token",
        "users"                              => "/api/users",
        "user"                               => "/api/users/{id}",
        "me"                                => "/api/users/me",
        "phone_verification_request"        => "/api/users/{id}/phone",
        "phone_verification_complete"       => "/api/users/{id}/phone/verify",
        "upload_kyc_doc"                    => "/api/users/{id}/upload-kyc-documents",
        "kyc_request"                       => "/api/users/{id}/verify",
        "estimate_transactions_cost"        => "/api/transactions/estimate",
        "transaction_create"               => "/api/users/{userId}/transactions",
        "transaction_cancel"                => "/api/users/{userId}/transactions/{transactionId}/cancel",
        "transaction"                       => "/api/transactions/{id}",
        "transactions"                      => "/api/transactions",
        "transactions_v2"                   => "/api/v2/transactions",
        "cryptos"                           => "/api/cryptocurrencies",
        "limits"                            => "/api/cryptocurrencies/limits",
        "prices"                            => "/api/prices",
    ];

    /**
     * User
     *
     * @var Models\User
     */
    private $users;

    /**
     * Transaction
     *
     * @var Models\Transaction
     */
    private $transactions;

    /**
     * Misc
     *
     * @var Models\Misc
     */
    private $misc;

    /**
     * Set Xanpool
     *
     */
    public function __construct($key, $secret)
    {
        $this->setAuthKeys($key, $secret);
    }

    /**
     * setKey()
     *
     * @return self
     */
    public function setAuthKeys($key, $secret)
    {
        $this->key = $key;

        $this->secret = $secret;

        return $this;
    }

    /**
     * getAuthKeys()
     *
     * @return array
     */
    public function getAuthKeys()
    {
        return [$this->key, $this->secret];
    }

    /**
     * getRoot()
     *
     * @return string
     */
    public function getRoot()
    {
        return $this->apiPath;
    }

    /**
     * getPath()
     *
     * @return string
     */
    public function getPath($handle)
    {
        return $this->paths[$handle] ?? false;
    }

    /**
     * request()
     *
     * @return Response
     */
    public function request($handle, $params = [], $data = [], $type = 'GET', $token=true)
    {
        return (new Request($this))->send($handle, $params, $data, $type, $token);
    }

    public function authToken($id)
    {
        $response = $this->request('oauth2-token', [], ['userId'=>$id], 'POST', false)->results();
        $this->token = $response['accessToken'];

        return $this->token;
    }

    /**
     * getToken()
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * users()
     *
     * @return Models\User
     */
    public function users()
    {
        if ($this->users) {
            return $this->users;
        }

        return ($this->users = (new User($this)));
    }

    /**
     * transactions()
     *
     * @return Models\Transaction
     */
    public function transactions()
    {
        if ($this->transactions) {
            return $this->transactions;
        }

        return ($this->transactions = (new Transaction($this)));
    }

    /**
     * misc()
     *
     * @return Models\Misc
     */
    public function misc()
    {
        if ($this->misc) {
            return $this->misc;
        }

        return ($this->misc = (new Misc($this)));
    }

}
