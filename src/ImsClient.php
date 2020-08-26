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
        $curlParams = 'images';
        $i = 1;
        foreach ($params as $key=>$param){
            $curlParams.=( ($i==1?'?':'&') .$key.'='.$param);
            $i++;
        }
        $this->curlParams = $curlParams;
        return $this->request();

    }



}