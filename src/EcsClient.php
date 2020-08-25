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
     * @param              $zone
     * @param              $projectId  项目ID
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
        $this->curlMethod   = 'POST';
        $this->curlParams = $projectId . '/cloudservers';
        $this->curlData   = [
            'server' => [
                'availability_zone' => $zone,
                'name'              => $instanceName,
                'imageRef'          => $imageId,
                'adminPass'         => $password,
                'isAutoRename'      => true,
                'root_volume'       => $systemVolume->getArray(),
                'data_volumes'      => $dataVolume->getArray(),
                'flavorRef'         => $instanceType,
                'vpcid'             => $vpcId,
                'nics'              => $nics,
                'count'             => $number,
                'publicip'          => [
                    'eip' => $eip->getArray(),
                ],
                'extendparam'       => [
                    "chargingMode" => "prePaid",
                    "periodType"   => "month",
                    "periodNum"    => $month,
                    "isAutoRenew"  => 'false',
                    "isAutoPay"    => 'true',
                ],
                'server_tags'       => [
                    [
                        'key'   => 'orderNo',
                        'value' => $orderNo,
                    ],
                ],


            ],
            //'dry_run' => true,

        ];

        return $this->request();

    }


    /**
     * 规格变更
     * https://support.huaweicloud.com/api-ecs/ecs_02_0209.html#ecs_02_0209__table7657338
     *
     * @param $projectId 项目ID
     * @param $instanceId
     * @param $flavorRef
     *
     * @author xietaotao
     */
    public function resize($projectId, $instanceId, $flavorRef)
    {

        $this->version    = 'v1.1';
        $this->curlMethod   = 'POST';
        $this->curlParams = $projectId . '/cloudservers/' . $instanceId . '/resize';
        $this->curlData   = [
            'resize' => [
                'flavorRef'   => $flavorRef,
                'mode'        => 'withStopServer ',//取值为withStopServer ，支持开机状态下变更规格。mode取值为withStopServer时，对开机状态的云服务器执行变更规格操作，系统自动对云服务器先执行关机，再变更规格，变更成功后再执行开机。
                'extendparam' => [
                    "isAutoPay" => true,
                ],
            ],
        ];

        return $this->request();

    }

    /**
     * 查询服务器详情
     *
     * @param $projectId
     * @param $instanceId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function describe($projectId, $instanceId)
    {

        $this->version    = 'v1';
        $this->curlMethod   = 'GET';
        $this->curlParams = $projectId . '/cloudservers/' . $instanceId;

        return $this->request();

    }

    /**
     * 查询服务器磁盘信息
     *
     * @param $projectId
     * @param $instanceId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function describeDisks($projectId, $instanceId)
    {

        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/' . $instanceId . '/block_device';

        return $this->request();

    }

    /**
     * 获取项目可用区
     *
     * @param $projectId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function novaListAvailabilityZone($projectId)
    {
        $this->version    = 'v2.1';
        $this->curlMethod   = 'GET';
        $this->curlParams = $projectId . '/os-availability-zone';

        return $this->request();

    }


    /**
     * 获取项目规格
     *
     * @param $projectId
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function getFlavors($projectId)
    {
        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/flavors';

        return $this->request();
    }


    /**
     * 查询任务的执行状态
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


    /**
     * 批量启动
     * 根据给定的云服务器ID列表，批量启动云服务器，一次最多可以启动1000台。
     * https://support.huaweicloud.com/api-ecs/ecs_02_0301.html#ecs_02_0301__table52132698163051
     *
     * @param       $projectId   项目ID
     * @param array $instanceIds 实例ID
     *
     * @author xietaotao
     */
    public function start($projectId, $instanceIds = [])
    {

        $servers = [];
        foreach ($instanceIds as $instanceId) {
            $servers[] = [
                'id' => $instanceId,
            ];
        }
        $this->version    = 'v1';
        $this->curlMethod   = 'POST';
        $this->curlParams = $projectId . '/cloudservers/action';
        $this->curlData   = [
            'os-start' => [
                'servers' => $servers,
            ],
        ];

        return $this->request();

    }

    /**
     * 批量重启云服务器
     * 根据给定的云服务器ID列表，批量重启云服务器，一次最多可以重启1000台。
     * https://support.huaweicloud.com/api-ecs/ecs_02_0302.html
     *
     * @param        $projectId   项目ID
     * @param array  $instanceIds 实例ID
     * @param string $type        重启类型：SOFT：普通重启。HARD：强制重启。
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function reStart($projectId, $instanceIds = [], $type = 'SOFT')
    {

        $servers = [];
        foreach ($instanceIds as $instanceId) {
            $servers[] = [
                'id' => $instanceId,
            ];
        }
        $this->version    = 'v1';
        $this->curlMethod   = 'POST';
        $this->curlParams = $projectId . '/cloudservers/action';
        $this->curlData   = [
            'reboot' => [
                'type'    => $type,
                'servers' => $servers,
            ],
        ];

        return $this->request();

    }

    /**
     * 批量关闭云服务器
     * 根据给定的云服务器ID列表，批量关闭云服务器，一次最多可以关闭1000台。
     * https://support.huaweicloud.com/api-ecs/ecs_02_0303.html
     *
     *
     * @param        $projectId   项目ID
     * @param array  $instanceIds 云服务器ID列表
     * @param string $type        SOFT：普通关机（默认）。 HARD：强制关机。
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function stop($projectId, $instanceIds = [], $type = 'SOFT')
    {

        $servers = [];
        foreach ($instanceIds as $instanceId) {
            $servers[] = [
                'id' => $instanceId,
            ];
        }
        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/action';
        $this->curlMethod   = 'POST';
        $this->curlData   = [
            'os-stop' => [
                'type'    => $type,
                'servers' => $servers,
            ],
        ];

        return $this->request();
    }

    /**
     * 批量修改弹性云服务器
     * 当前仅支持批量修改云服务器名称，一次最多可以修改1000台。
     * https://support.huaweicloud.com/api-ecs/ecs_02_0305.html
     *
     * @param       $projectId   项目ID
     * @param array $instanceIds 云服务器ID列表
     * @param       $name        弹性云服务器修改后的名称。
     * @param bool  $dryRun      是否只预检此次请求
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function reName($projectId, $instanceIds = [], $name, $dryRun = false)
    {

        $servers = [];
        foreach ($instanceIds as $instanceId) {
            $servers[] = [
                'id' => $instanceId,
            ];
        }
        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/server-name';
        $this->curlMethod   = 'PUT';
        $this->curlData   = [
            'name'    => $name,
            'dry_run' => $dryRun,
            'servers' => $servers,
        ];

        return $this->request();

    }


    /**
     * 批量重置弹性云服务器密码
     * 批量重置弹性云服务器管理帐号（root用户或Administrator用户）的密码。
     * https://support.huaweicloud.com/api-ecs/ecs_02_0306.html
     *
     * @param       $projectId   项目ID
     * @param array $instanceIds 待批量重置密码的弹性云服务器ID信息
     * @param       $newPassword 新密码 规则参阅华为云文档
     * @param bool  $dryRun      是否只预检此次请求
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function resetPass($projectId, $instanceIds = [], $newPassword, $dryRun = false)
    {


        $servers = [];
        foreach ($instanceIds as $instanceId) {
            $servers[] = [
                'id' => $instanceId,
            ];
        }
        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/os-reset-passwords';
        $this->curlMethod   = 'PUT';
        $this->curlData   = [
            'new_password' => $newPassword,
            'dry_run'      => $dryRun,
            'servers'      => $servers,
        ];

        return $this->request();
    }

    /**
     * 获取VNC远程登录地址
     * 获取弹性云服务器VNC远程登录地址。
     * https://support.huaweicloud.com/api-ecs/ecs_02_0208.html
     *
     * @param $projectId  项目ID。
     * @param $instanceId 云服务器ID。
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function vnc($projectId, $instanceId)
    {


        $this->version    = 'v1';
        $this->curlParams = $projectId . '/cloudservers/' . $instanceId . '/remote_console';
        $this->curlMethod   = 'POST';
        $this->curlData   = [
            'remote_console' => [
                'protocol' => 'vnc',
                'type'     => 'novnc',
            ],
        ];

        return $this->request();
    }


    /**
     * 切换弹性云服务器操作系统（安装Cloud-init）
     * @param $projectId
     * @param $instanceId
     * @param $adminpass
     * @param $imageid
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function changeos($projectId, $instanceId,$adminpass,$imageid)
    {


        $this->version    = 'v1';
        $this->curlMethod   = 'POST';
        $this->curlParams = $projectId . '/cloudservers/' . $instanceId . '/changeos';
        $this->curlData   = [
            'os-change' => [
                'adminpass' => $adminpass,
                'imageid'     => $imageid,
                'mode'     => 'withStopServer',
            ],
        ];

        return $this->request();
    }

}