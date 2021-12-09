<?php

namespace App\Http\Controllers\Xanpool\Models;

use App\Http\Controllers\Xanpool\Xanpool;

class User
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
        return $this->xanpool->request('user',['id'=>$id], [], 'GET', false)->results();
    }

    /**
     * me()
     *
     * @return array
     */
    public function me()
    {
        return $this->xanpool->request('me',[], [],'GET')->results();
    }

    /**
     * signup()
     *
     * @return array
     */
    public function signup($data)
    {
        return $this->xanpool->request('users',[], $data,'POST', false)->results();
    }

    /**
     * requestPhoneVerification()
     *
     * @return array
     */
    public function requestPhoneVerification($id, $data)
    {
        return $this->xanpool->request('phone_verification_request',['id'=>$id], $data, 'PUT')->results();
    }

    /**
     * completePhoneVerification()
     *
     * @return array
     */
    public function completePhoneVerification($id, $code)
    {
        return $this->xanpool->request('phone_verification_complete',['id'=>$id], ['code'=>$code], 'PUT')->results();
    }

    /**
     * uploadKycDoc()
     * @param $type = ['front', 'back', 'addressProof', 'kycVideo']
     * @param binary $file
     * @param string $filename
     * @return array
     */
    public function uploadKycDoc($id, $type, $filename, $file)
    {
        $data = [
            'file' => $file,
            'filename' => $filename,
            'type' => $type,
        ];

        return $this->xanpool->request('upload_kyc_doc',['id'=>$id], $data, 'POST')->results();
    }

    /**
     * KycRequest()
     *
     * @return array
     */
    public function kycRequest($id, $address)
    {
        return $this->xanpool->request('kyc_request',['id'=>$id], ['address'=>$address], 'PUT')->results();
    }

}
