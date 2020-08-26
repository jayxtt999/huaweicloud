<?php
/**
 * Created by PhpStorm.
 * User: naix
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


class ImsClient extends Client
{

    public $domain = 'ims';

    public function Images($params=[])
    {
        $this->version    = 'v2';
        $curlParams = 'images?';
        foreach ($params as $key=>$param){
            $curlParams.=($key.'='.$param);
        }
        $this->curlParams = $curlParams;
        return $this->request();



    }



}