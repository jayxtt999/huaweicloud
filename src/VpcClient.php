<?php
/**
 * Created by PhpStorm.
 * User: naix
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


class VpcClient extends Client
{
    public $domain = 'vpc';

    /**
     * 查询子网列表
     * https://support.huaweicloud.com/api-vpc/vpc_subnet01_0003.html
     *
     * @param $projectId
     * @param $vpcId
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function listSubnets($projectId, $vpcId)
    {
        $this->version  = 'v1';
        $this->curlPath = "{$projectId}/subnets?vpc_id/{$vpcId}";

        return $this->request();

    }


    /**
     * 查询VPC列表
     * https://support.huaweicloud.com/api-vpc/vpc_api01_0003.html
     *
     * @param $projectId
     * @param $query
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function listVpcs($projectId, $query)
    {

        $this->version  = 'v1';
        $this->curlPath = "{$projectId}/vpcs";

        return $this->request();

    }

    /**
     * 创建VPC
     * https://support.huaweicloud.com/api-vpc/vpc_api01_0001.html
     *
     * @param        $cidr
     * @param string $name
     * @param string $description
     * @param bool   $enterpriseProjectId
     *
     * @author naix
     */
    public function createVpc($projectId, $cidr, $name = 'default_vpc', $description = '默认Vpc', $enterpriseProjectId = false)
    {


        $this->version    = 'v1';
        $this->curlMethod = 'POST';
        $this->curlPath   = "{$projectId}/vpcs";
        $this->curlData   = [
            'vpc' => [
                'name'        => $name,
                'description' => $description,
                'cidr'        => $cidr,
            ],
        ];
        if ($enterpriseProjectId) {
            $this->curlData['enterprise_project_id'] = $enterpriseProjectId;
        }

        return $this->request();
    }


    /**
     * 创建子网
     * https://support.huaweicloud.com/api-vpc/vpc_subnet01_0001.html
     *
     *
     * @param $vpcId       子网所在VPC标识
     * @param $name        功能说明：子网名称  取值范围：1-64个字符，支持数字、字母、中文、_(下划线)、-（中划线）、.（点）
     * @param $cidr        功能说明：子网的网段 取值范围：必须在vpc对应cidr范围内 约束：必须是cidr格式。掩码长度不能大于28
     * @param $gatewayIp   功能说明：子网的网关 取值范围：子网网段中的IP地址 约束：必须是ip格式
     * @param $description 功能说明：子网描述 取值范围：0-255个字符，不能包含“<”和“>”。
     *
     * @author naix
     */
    public function createSubNet($projectId,$vpcId, $name, $cidr, $gatewayIp, $description = '')
    {

        $this->version    = 'v1';
        $this->curlMethod = 'POST';
        $this->curlPath   = "{$projectId}/subnets";
        $this->curlData   = [
            'subnet' => [
                'vpc_id'      => $vpcId,
                'name'        => $name,
                'description' => $description,
                'cidr'        => $cidr,
                'gateway_ip'  => $gatewayIp,
            ],
        ];
        return $this->request();

    }
}