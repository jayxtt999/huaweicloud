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

    /**
     * 创建云硬盘
     * https://support.huaweicloud.com/api-evs/evs_04_2003.html#section3
     *
     * @param $projectId 项目ID
     * @param $zone  指定要创建云硬盘的所属AZ，若指定的AZ不存在，则创建云硬盘失败
     * @param $volumeType 云硬盘类型。 目前支持“SSD”，“GPSSD”和“SAS”三种。
     * @param $size 云硬盘大小，单位为GB
     * @param $name 云硬盘名称
     * @param $diskCount 批量创云硬盘的个数。如果无该参数，表明只创建1个云硬盘，目前最多支持批量创建100个
     * @param $tags 创建云硬盘的时候，给云硬盘绑定标签。
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function createDisks($projectId, $zone, $volumeType, $size, $name, $diskCount,$tags=[])
    {

        $this->version    = 'v2.1';
        $this->curlMethod = 'POST';
        $this->curlParams = $projectId . '/cloudvolumes';

        $this->curlData = [

            'volume'   => [
                "name"              => $name,
                "availability_zone" => $zone,
                "volume_type"       => $volumeType,
                "size"              => $size,
                "count"             => $diskCount,
            ],
            'bssParam' => [
                "chargingMode" => "postPaid",
                "isAutoPay"    => true,
            ],
        ];
        if($tags){
            $this->curlData['volume']['tags'] = $tags;
        }
        return $this->request();

    }

    /**
     * 扩容云硬盘
     * https://support.huaweicloud.com/api-evs/evs_04_2004.html
     * @param $projectId 项目ID
     * @param $volumeId 云硬盘ID 扩容后的云硬盘大小，单位为GB。 扩容后的云硬盘容量范围：大于原有云硬盘容量~云硬盘最大容量（数据盘为32768GB；系统盘为1024GB）
     * @param $newSize
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function resizeDisk($projectId,$volumeId,$newSize){

        $this->version    = 'v2.1';
        $this->curlMethod = 'POST';
        $this->curlParams = $projectId . '/cloudvolumes/'.$volumeId.'/action';
        $this->curlData = [

            'os-extend'   => [
                "new_size"              => $newSize,
            ],
            'bssParam' => [
                "chargingMode" => "postPaid",
                "isAutoPay"    => "true",
            ],
        ];

        return $this->request();
    }


    /**
     * 查询所有的可用分区信息
     * https://support.huaweicloud.com/api-evs/evs_04_2081.html
     *
     * @param $projectId 项目ID
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function getAvailabilityZone($projectId){
        $this->version    = 'v2';
        $this->curlParams = $projectId . '/os-availability-zone';
        return $this->request();

    }



    /**
     * 查询任务的执行状态
     * https://support.huaweicloud.com/api-evs/evs_04_0054.html
     *
     * @param $projectId
     * @param $jobId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function jobs($projectId, $jobId)
    {
        $this->version    = 'v1';
        $this->curlParams = $projectId . '/jobs/' . $jobId;
        $this->curlMethod   = 'GET';
        return $this->request();
    }
}