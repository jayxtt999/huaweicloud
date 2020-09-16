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
     * 查询VPC列表
     * https://support.huaweicloud.com/api-vpc/vpc_api01_0003.html
     *
     * @param $projectId
     * @param $vpcId
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function ListSubnets($projectId, $vpcId)
    {
        $this->version    = 'v1';
        $this->curlPath = "{$projectId}/subnets?vpc_id/{$vpcId}";

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
        $this->curlPath = "{$projectId}/vpcs";
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

}