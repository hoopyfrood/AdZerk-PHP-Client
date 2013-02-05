<?php

/**
 * Adzerk API thin client
 * Jul 2012 - @hubail
 *
 * Usage Tips, Advertisers as example, consistent behaviour for all endpoints:
 *
 * List Advertisers
 * // no arguments
 * $adzerk->advertiser();
 *
 * Create Advertiser
 * // arg1: null, arg2: array of data (plain-text associative PHP array)
 * $adzerk->advertiser(null, array());
 *
 * Update Advertiser
 * // arg1: (int) Advertiser ID, arg2: array of data (plain-text associative PHP array)
 * $adzerk->advertiser($advertiserId, array());
 *
 * All responses are returned by default, as an associative PHP array
 *
 */

class Adzerk
{
    private $apiKey;
    private $apiBase = 'http://api.adzerk.net/v1/';

    public function __construct($key)
    {
        $this->apiKey = $key;
    }
    // HTTP Requests
    /**
     * GET
     */
    private function get($func, $var = null)
    {
        $url = $this->apiBase . $func;
        $url .= is_null($var) ? '' : '/' . $var;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Adzerk-ApiKey: ' . $this->apiKey));

        $result = curl_exec($ch);
        if ($result === false) {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);

        return json_decode($result);
    }
    /**
     * POST [and PUT]
     */
    private function post($func, $var = null, $data = null, $is_put = false)
    {
        $url = $this->apiBase . $func;
        $url .= is_null($var) ? '' : '/' . $var;

        $data = $func . '=' . urlencode(json_encode($data));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Adzerk-ApiKey: ' . $this->apiKey));
        if ($is_put) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        if ($result === false) {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);

        return json_decode($result);
    }

    private function put($func, $var = null, $data = null)
    {
        return $this->post($func, $var, $data, true);
    }

    public function __call($func, $param)
    {
        // GET
        if (count($param) < 2) {
            return $this->get($func, !isset($param[0]) ? null : $param[0]);
        }

        // POST
        if (count($param) >= 2 && is_null($param[0])) {
            return $this->post($func, $param[0], $param[1]);
        }

        // PUT
        if (count($param) >= 2 && !is_null($param[0])) {
            return $this->put($func, $param[0], $param[1]);
        }
    }
}
