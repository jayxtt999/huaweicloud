<?php
/**
 * Created by PhpStorm.
 * User: xietaotao
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


class EvsClient extends Client
{
    public $domain = 'evs';

    /**
     * 查询磁盘信息
     *
     * @param $projectId
     * @param $instanceId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function describeDisks($projectId, $volumeId)
    {

        $this->version    = 'v2';
        $this->curlMethod = 'GET';
        $this->curlParams = $projectId . '/cloudvolumes/' . $volumeId;

        return $this->request();

    }

}