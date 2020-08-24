<?php
/**
 * Created by PhpStorm.
 * User: xietaotao
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


class IamClient extends Client
{

    public $domain = 'iam';

    public function ListRegions()
    {
        $this->version    = 'v3';
        $this->curlParams = 'regions';
        return $this->request();



    }

    public function Projects()
    {
        $this->version    = 'v3';
        $this->curlParams = 'projects';
        return $this->request();



    }


}