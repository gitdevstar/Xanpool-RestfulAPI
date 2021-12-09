<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

use Illuminate\Http\Request;
use App\Http\Controllers\Xanpool\Xanpool;
use Illuminate\Support\Facades\Log;

class XanpoolApiController extends Controller
{
    /**
     * Response array
     *
     * @var Xanpool
     */
    private $xanpool;


    public function __construct()
    {
        if($this->xanpool == null)
            $this->xanpool = new Xanpool(config('xanpool.api_key'), config('xanpool.secret_key'));
    }

    public function signup(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'firstName' => ['required'],
            'lastName' => ['required'],
        ]);

        $data = [
            'email' => $request->email,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
        ];

        try {
            $response = $this->xanpool->users()->signup($data);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        $id = $response['id'];
        $token = "";
        try {
            $token = $this->xanpool->authToken($id);
        } catch (\Throwable $th) {
            Log::info($id.": auth token issue --> ".$th->getMessage());
            //throw $th;
        }

        return response()->json(['id' => $id, 'token' =>$token]);
    }

    public function requestPhoneVerification(Request $request)
    {
        $this->validate($request, [
            'phone' => ['required'],
            'code' => ['required'],
            'id' => ['required']
        ]);

        $id = $request->id;

        $data = [
            'phone' => $request->phone,
            'code' => $request->code,
        ];

        try {
            $this->xanpool->authToken($id);
        } catch (\Throwable $th) {
            Log::info($id.": auth token issue --> ".$th->getMessage());
            //throw $th;
        }

        try {
            $response = $this->xanpool->users()->requestPhoneVerification($id, $data);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['status' => true]);
    }

    public function completePhoneVerification(Request $request)
    {
        $this->validate($request, [
            'code' => ['required'],
            'id' => ['required']
        ]);

        $id = $request->id;
        $code = $request->code;

        try {
            $this->xanpool->authToken($id);
        } catch (\Throwable $th) {
            Log::info($id.": auth token issue --> ".$th->getMessage());
            //throw $th;
        }

        try {
            $response = $this->xanpool->users()->completePhoneVerification($id, $code);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['status' => true]);
    }

    public function uploadKycDoc(Request $request)
    {
        $this->validate($request, [
            'id' => ['required'],
            'file' => ['required'],
            'filename' => ['required'],
            'type' => ['required']
        ]);

        $id = $request->id;
        $type = $request->type;
        $filename = $request->filename;
        $file = $request->file;



        try {
            $this->xanpool->authToken($id);
        } catch (\Throwable $th) {
            Log::info($id.": auth token issue --> ".$th->getMessage());
            //throw $th;
        }

        try {
            $response = $this->xanpool->users()->uploadKycDoc($id, $type, $filename, $file);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['status' => $response['status']]);
    }

    public function kycRequest(Request $request)
    {
        $this->validate($request, [
            'id' => ['required'],
            'address' => ['required'],
        ]);

        $id = $request->id;
        $address = $request->address;

        try {
            $this->xanpool->authToken($id);
        } catch (\Throwable $th) {
            Log::info($id.": auth token issue --> ".$th->getMessage());
            //throw $th;
        }

        try {
            $response = $this->xanpool->users()->kycRequest($id, $address);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['status' => true]);
    }

    public function supportedCryptos(Request $request)
    {

        try {
            $response = $this->xanpool->misc()->supportedCryptos();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['data' => $response]);
    }

    public function limits(Request $request)
    {

        try {
            $response = $this->xanpool->misc()->limits();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['data' => $response]);
    }

    public function prices(Request $request)
    {

        $this->validate($request, [
            'type' => ['required'],
            'currencies' => ['required'],
            'cryptoCurrencies' => ['required'],
        ]);

        $type = $request->type;
        $currencies = $request->currencies;
        $cryptoCurrencies = $request->cryptoCurrencies;

        try {
            $response = $this->xanpool->misc()->prices($type, $currencies, $cryptoCurrencies);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['data' => $response]);
    }

    public function estimateCost(Request $request)
    {
        $this->validate($request, [
            'type' => ['required'],
        ]);

        $type = $request->type;

        $data = [
            'type' => $type
        ];

        if($request->has('cryptoCurrency'))
            $data['cryptoCurrency'] = $request->cryptoCurrency;
        if($request->has('method'))
            $data['method'] = $request->method;
        if($request->has('currency'))
            $data['currency'] = $request->currency;
        if($request->has('fiat'))
            $data['fiat'] = $request->fiat;
        if($request->has('crypto'))
            $data['crypto'] = $request->crypto;

        try {
            $response = $this->xanpool->transactions()->estimateCost($data);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['data' => $response]);
    }

    public function createTransaction(Request $request)
    {
        $this->validate($request, [
            'type' => ['required'],
            'id' => ['required'],
        ]);

        $type = $request->type;
        $id = $request->id;

        $data = [
            'type' => $type
        ];

        try {
            $this->xanpool->authToken($id);
        } catch (\Throwable $th) {
            Log::info($id.": auth token issue --> ".$th->getMessage());
            //throw $th;
        }

        if($request->has('cryptoCurrency'))
            $data['cryptoCurrency'] = $request->cryptoCurrency;
        if($request->has('method'))
            $data['method'] = $request->method;
        if($request->has('fiat'))
            $data['fiat'] = $request->fiat;
        if($request->has('crypto'))
            $data['crypto'] = $request->crypto;
        if($request->has('wallet'))
            $data['wallet'] = $request->wallet;
        if($request->has('destination'))
            $data['destination'] = $request->destination;
        if($request->has('chain'))
            $data['chain'] = $request->chain;
        if($request->has('miningPlan'))
            $data['miningPlan'] = $request->miningPlan;

        try {
            $response = $this->xanpool->transactions()->create($id, $type, $data);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['data' => $response]);
    }

    public function cancelTransaction(Request $request)
    {
        $this->validate($request, [
            'txn_id' => ['required'],
            'id' => ['required'],
        ]);

        $txn_id = $request->txn_id;
        $id = $request->id;

        try {
            $this->xanpool->authToken($id);
        } catch (\Throwable $th) {
            Log::info($id.": auth token issue --> ".$th->getMessage());
            //throw $th;
        }

        try {
            $response = $this->xanpool->transactions()->cancel($id, $txn_id);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

        return response()->json(['data' => $response]);
    }

    /*
    * hook()
    * currently only TRANSACTION_UPDATED
    * @type ['buy', 'sell']
    * @status ['pending', 'completed', 'fiat_received', 'btc_in_mempool', 'btc_received', 'expired_fiat_not_received', 'expired_btc_not_in_mempool', 'payout_failed', 'refunded', 'cancelled']
    * @blockchainTxId Blockchain transaction id for successful transactions
    * @internalTxId Internal transaction id if internalAddress was used
    * @wallet Blockchain wallet for buying transaction
    * @depositWallets Deposit wallets for a selling transaction. An object containing bitcoin, legacyBitcoin, ethereum addresses.
    */
    public function hook(Request $request)
    {
        $payload = $request->payload;
        $userId = $request->userId;
        $type = $request->type;
        $status = $request->status;
        $wallet = $request->wallet;
        $depositWallets = $request->depositWallets;
        $method = $request->method;
        $currency = $request->currency;
        $cryptoCurrency = $request->cryptoCurrency;
        $userCountry = $request->userCountry;
        $blockchainTxId = $request->blockchainTxId;
        $internalTxId = $request->internalTxId;

        Log::info($userId." user transaction status ---> " . $status);

    }
}
