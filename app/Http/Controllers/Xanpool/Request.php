<?php

namespace App\Http\Controllers\Xanpool;

use GuzzleHttp\Client;
use App\Http\Controllers\Xanpool\Response;

class Request
{
    private $xanpool;

    /**
     * Guzzle Client
     *
     * @return GuzzleHttp\Client
     */
    private $client;

    /**
     * Start the class()
     *
     */
    public function __construct(Xanpool $xanpool, $timeout = 4)
    {
        $this->xanpool = $xanpool;

        $this->client = new Client([
            'base_uri' => $this->xanpool->getRoot(),
            'timeout'  => $timeout
        ]);
    }

    /**
     * send()
     *
     * Send request
     *
     * @return Response
     */
    public function send($handle, $params = [], $data = [], $type = 'GET', $token = true)
    {
        // build and prepare our full path rul
        $url = $this->prepareUrl($handle, $params);

        // lets track how long it takes for this request
        $seconds = 0;

        // push request
        if($token)
            $request = $this->client->request($type, $url, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->xanpool->getToken()
                ],
                'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$seconds) {
                    $seconds = $stats->getTransferTime();
                }
            ]);
        else
            $request = $this->client->request($type, $url, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'auth' => [$this->xanpool->getAuthKeys()[0], $this->xanpool->getAuthKeys()[1]],
                'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$seconds) {
                    $seconds = $stats->getTransferTime();
                }
            ]);

        // send and return the request response
        return (new Response($request, $seconds));
    }

    /**
     * prepareUrl()
     *
     * Get and prepare the url
     *
     * @return string
     */
    private function prepareUrl($handle, $segments = [])
    {
        $url = $this->xanpool->getPath($handle);

        foreach($segments as $segment=>$value) {
            $url = str_replace('{'.$segment.'}', $value, $url);
        }

        return $url;
    }
}
