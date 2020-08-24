<?php
/**
 * Created by PhpStorm.
 * User: xietaotao
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;




use app\huawei\service\Sdk\DataInterFace\DataVolume;
use app\huawei\service\Sdk\DataInterFace\Eip;
use app\huawei\service\Sdk\DataInterFace\SystemVolume;

class EcsClient extends Client
{

    public $domain = 'ecs';


    /**
     * 创建
     * @param              $zone
     * @param              $projectId
     * @param              $instanceType
     * @param              $imageId
     * @param              $vpcId
     * @param              $nics
     * @param Eip          $eip
     * @param              $month
     * @param SystemVolume $systemVolume
     * @param DataVolume   $dataVolume
     * @param              $password
     * @param              $number
     * @param              $orderNo
     * @param              $instanceName
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function create($zone, $projectId, $instanceType, $imageId, $vpcId, $nics, Eip $eip, $month, SystemVolume $systemVolume, DataVolume $dataVolume, $password, $number, $orderNo, $instanceName)
    {

        $this->version    = 'v1.1';
        $this->curlParams = $projectId . '/cloudservers';
        $this->curlType = 'POST';
        $this->curlData = [
            'server'  => [
                'availability_zone' => $zone,
                'name'              => $instanceName,
                'imageRef'          => $imageId,
                'adminPass'    => $password,
                'isAutoRename' => true,
                'root_volume'  => $systemVolume->getArray(),
                'data_volumes' => $dataVolume->getArray(),
                'flavorRef'    => $instanceType,
                'vpcid'        => $vpcId,
                'nics'         => $nics,
                'count'        => $number,
                'publicip'     => [
                    'eip' => $eip->getArray(),
                ],
                'extendparam'  => [
                    "chargingMode" => "prePaid",
                    "periodType"   => "month",
                    "periodNum"    => $month,
                    "isAutoRenew"  => 'false',
                    "isAutoPay"    => 'true',
                ],
                'server_tags'  => [
                    [
                        'key'=>'orderNo',
                        'value'=>$orderNo,
                    ]
                ],


            ],
            //'dry_run' => true,

        ];
        return  $this->request();

    }


    /**
     * https://support.huaweicloud.com/api-ecs/ecs_02_0209.html#ecs_02_0209__table7657338
     *
     * @param $projectId
     * @param $instanceId
     * @param $flavorRef
     * @author xietaotao
     */
    public function resize($projectId,$instanceId,$flavorRef){

        $this->version    = 'v1.1';
        $this->curlParams = $projectId . '/cloudservers/'.$instanceId.'/resize';
        $this->curlType = 'POST';
        $this->curlData = [
            'resize'=>[
                'flavorRef'=>$flavorRef,
                'mode'=>'withStopServer ',//取值为withStopServer ，支持开机状态下变更规格。mode取值为withStopServer时，对开机状态的云服务器执行变更规格操作，系统自动对云服务器先执行关机，再变更规格，变更成功后再执行开机。
                'extendparam'=>[
                    "isAutoPay"=> true
                ]
            ]
        ];
    }

    /**
     * 查询服务器详情
     * @param $projectId
     * @param $instanceId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function describe($projectId,$instanceId){

        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/'.$instanceId;
        return  $this->request();

    }

    /**
     * 查询服务器磁盘信息
     * @param $projectId
     * @param $instanceId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function describeDisks($projectId,$instanceId){

        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/'.$instanceId.'/block_device';
        return  $this->request();

    }

    /**
     * 获取项目可用区
     * @param $projectId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function novaListAvailabilityZone($projectId)
    {
        $this->version    = 'v2.1';
        $this->curlParams = $projectId . '/os-availability-zone';

        return $this->request();

    }


    /**
     * 获取项目规格
     * @param $projectId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function getFlavors($projectId){
        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/flavors';
        return $this->request();
    }


    /**
     * 查询任务的执行状态
     * @param $projectId
     * @param $jobId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function jobs($projectId,$jobId){
        $this->version    = 'v1';
        $this->curlParams = $projectId . '/jobs/'.$jobId;
        return $this->request();
    }


}