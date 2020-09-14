<?php
/**
 * Created by PhpStorm.
 * User: naix
 * Date: 2020/8/20
 * Time: 11:30
 */

namespace HwCloud;


use HwCloud\Common\Request;
use HwCloud\Common\Signer;

class Client
{

    /**
     * @var Signer
     */
    public $signer;
    public $error;
    public $domain           = '';
    public $protocol         = 'https';
    public $suffix           = '.com';
    public $endpoint         = '';
    public $region;
    public $version;
    public $curlUrl          = '';
    public $fixedCurlUrl     = false;
    public $noEndpoint       = false;
    public $curlParams;
    public $curlMethod       = 'GET';
    public $curlData;
    public $returnHeader     = false;//是否需要返回header头信息
    public $returnHeaderData = [];//返回的header头信息
    public $endpointList
                             = array(
            'cn-northeast-1' => array('key' => 'cn-northeast-1', 'name' => '东北-大连', 'protocol' => 'https', 'suffix' => '.com'),
            'af-south-1'     => array('key' => 'af-south-1', 'name' => '非洲-约翰内斯堡', 'protocol' => 'https', 'suffix' => '.com'),
            'cn-north-4'     => array('key' => 'cn-north-4', 'name' => '华北-北京四', 'protocol' => 'https', 'suffix' => '.com'),
            'cn-north-1'     => array('key' => 'cn-north-1', 'name' => '华北-北京一', 'protocol' => 'https', 'suffix' => '.com'),
            'cn-east-2'      => array('key' => 'cn-east-2', 'name' => '华东-上海二', 'protocol' => 'https', 'suffix' => '.com'),
            'cn-east-3'      => array('key' => 'cn-east-3', 'name' => '华东-上海一', 'protocol' => 'https', 'suffix' => '.com'),
            'cn-south-1'     => array('key' => 'cn-south-1', 'name' => '华南-广州', 'protocol' => 'https', 'suffix' => '.com'),
            'eu-west-0'      => array('key' => 'eu-west-0', 'name' => '欧洲-巴黎', 'protocol' => 'https', 'suffix' => '.com'),
            'cn-southwest-2' => array('key' => 'cn-southwest-2', 'name' => '西南-贵阳一', 'protocol' => 'https', 'suffix' => '.com'),
            'ap-southeast-2' => array('key' => 'ap-southeast-2', 'name' => '亚太-曼谷', 'protocol' => 'https', 'suffix' => '.com'),
            'ap-southeast-1' => array('key' => 'ap-southeast-1', 'name' => '亚太-香港', 'protocol' => 'https', 'suffix' => '.com'),
            'ap-southeast-3' => array('key' => 'ap-southeast-3', 'name' => '亚太-新加坡', 'protocol' => 'https', 'suffix' => '.com'),
        );

    function __construct(Signer $signer, $region)
    {
        $this->signer = $signer;
        $this->region = $region;
    }

    /**
     * @param $url
     * @param $params
     *
     * @return string
     */
    public function addParams()
    {

        if (is_array($this->curlParams)) {
            $i = 0;
            foreach ($this->curlParams as $key => $item) {
                $tag           = ($i == 0 ? '?' : '&');
                $this->curlUrl .= $tag;
                $this->curlUrl .= ($key . '=' . $item);
            }
        } else {
            $this->curlUrl .= $this->curlParams;
        }
    }


    public function objectToArray($object)
    {
        $object = (array)$object;
        foreach ($object as $key => $value) {
            if (gettype($value) == 'resource') {
                return;
            }
            if (gettype($value) == 'object' || gettype($value) == 'array') {
                $object[$key] = (array)$this->objectToArray($value);
            }
        }

        return $object;
    }


    public function init()
    {
        if ($this->curlUrl && $this->fixedCurlUrl) {
            return true;
        }
        if (!$this->endpoint) {
            $this->endpoint = isset($this->endpointList[$this->region]) ? $this->endpointList[$this->region]['key'] : '';
            $this->protocol = isset($this->endpointList[$this->region]) ? $this->endpointList[$this->region]['protocol'] : '';
            $this->suffix   = isset($this->endpointList[$this->region]) ? $this->endpointList[$this->region]['suffix'] : '';
        }
        if (!$this->endpoint && false === $this->noEndpoint) {
            $this->error = '获取endpoint失败';

            return false;
        }
        if (false === $this->noEndpoint) {
            $this->curlUrl = $this->protocol . '://' . $this->domain . '.' . $this->endpoint . '.myhuaweicloud' . $this->suffix . '/';
        } else {
            $this->curlUrl = 'https://' . $this->domain . '.myhuaweicloud' . '.com/';
        }
        if ($this->version) {
            $this->curlUrl .= ($this->version . '/');
        }
        $this->addParams();

        return true;
    }

    /**
     * @return array|bool|mixed|void
     * @author naix
     */
    public function request()
    {

        $res = $this->init();
        if (!$res) {
            return false;
        }
        if ($this->curlData) {
            $body = json_encode($this->curlData);
        } else {
            $body = '';
        }
        $req          = new Request($this->curlMethod, $this->curlUrl);
        $req->headers = array(
            'content-type' => 'application/json;charset=UTF-8',
        );

        $req->body = $body;
        if ($this->returnHeader) {
            $req->curlopt_header = true;
        }
        $curl     = $this->signer->Sign($req);
        $response = curl_exec($curl);
        $status   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($this->returnHeader) {
            $headerTotal = strlen($response);
            $headerSize  = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $bodySize    = $headerTotal - $headerSize;
            $header      = substr($response, 0, $headerSize);
            $pHeader     = explode("\r\n", $header);
            $headerArr   = array();
            foreach ($pHeader as $value) {
                if (strpos($value, ':') !== false) {
                    $a               = explode(":", $value);
                    $key             = $a[0];
                    $v               = $a[1];
                    $headerArr[$key] = $v;
                } else {
                    array_push($headerArr, $value);
                }
            }
            $this->returnHeaderData = $headerArr;
            $response               = substr($response, $headerSize, $bodySize);
        }
        if (!in_array($status, [200, 201, 202])) {
            $error = [
                'http_code' => $status,
                'url'       => $this->curlUrl,
                'param'     => $this->curlData,
                'method'    => $this->curlMethod,
            ];
            if ($response) {
                $responseDe = json_decode($response);
                $responseDe = $this->objectToArray($responseDe);
                if (isset($responseDe['error'])) {
                    $error['code']    = $responseDe['error']['code'];
                    $error['message'] = $responseDe['error']['message'];
                } else {
                    $error['message'] = $response;
                }
            }
            $this->error = json_encode($error);

            return false;
        }
        curl_close($curl);
        $response = json_decode($response);
        $response = $this->objectToArray($response);

        return $response;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }


}