<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2018/6/21
 * Time: 14:34
 */

namespace Weimob\Api;

use Requests;
use Exception;

class Api
{
    private $accesstoken;       //access token
    private $headers = [];      //request header

    const BASE_URL = 'https://dopen.weimob.com/api/1_0/';

    public function __construct($accesstoken = '')
    {
        $this->accesstoken = $accesstoken;
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function addHeaders($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * @param string $accesstoken
     */
    public function setAccesstoken($accesstoken)
    {
        $this->accesstoken = $accesstoken;
    }

    /**
     * send request and get data
     * @param $api      weimob api name
     * @param $data     post params data
     * @return mixed    return result
     * @throws Exception    throw error
     */
    public function send($api, $data)
    {
        $url = self::BASE_URL . $api;
        $response = Requests::post($url, $this->headers  ,$data);
        $result = json_decode($response->body, true);
        if (empty($result)) {
            throw new Exception('bad request', -1);
        }
        if ($result['code']['errorcode'] == 0) {
            return $result['data'];
        } else {
            throw new Exception($result['code']['errmsg'], $result['code']['errorcode']);
        }
    }
}