<?php
/**
 * Created by PhpStorm.
 * User: xietaotao
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


class EipClient extends Client
{
    public $domain = 'vpc';

    public function describeIps($projectId, $marker, $limit)
    {
        $this->version    = 'v1';
        $this->curlParams = "{$projectId}/publicips";

        return $this->request();

    }

    /**
     * 更新包周期带宽
     * https://support.huaweicloud.com/api-eip/eip_apisharedbandwidth_0006.html
     *
     * @param $projectId 项目ID
     * @param $bandwidthId 带宽唯一标识
     * @param $size
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function resetMaxBandwidthV2($projectId, $bandwidthId, $size)
    {
        $this->version    = 'v2.0';
        $this->curlMethod = 'PUT';
        $this->curlParams = "{$projectId}/bandwidths/" . $bandwidthId;
        $this->curlData   = [
            'bandwidth' => ['size' => $size],
            'bandwidth' => ['is_auto_pay' => true],
        ];

        return $this->request();

    }


}