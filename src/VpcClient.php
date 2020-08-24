<?php
/**
 * Created by PhpStorm.
 * User: xietaotao
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


class VpcClient extends Client
{
    public $domain = 'vpc';

    public function ListSubnets($projectId,$vpcId)
    {
        $this->version    = 'v1';
        $this->curlParams = "{$projectId}/subnets?vpc_id/{$vpcId}";

        return $this->request();

    }



}