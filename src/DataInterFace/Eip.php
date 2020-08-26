<?php
/**
 * Created by PhpStorm.
 * User: naix
 * Date: 2020/8/20
 * Time: 16:08
 */

namespace HwCloud\DataInterFace;


class Eip implements AttributesInterface
{

    public $ipType;
    public $bandwidthSize;
    public $shareType = 'PER';

    public function getArray()
    {
        return [
            'iptype'    => $this->ipType,
            'bandwidth' => [
                'size'      => $this->bandwidthSize,
                'sharetype' => $this->shareType,
            ],
        ];
    }


    /**
     * 弹性公网IP的类型 取值范围
     * https://support.huaweicloud.com/api-eip/eip_api_0001.html#eip_api_0001__zh-cn_topic_0201534274_table4491214
     *
     * @param $region
     *
     * 东北-大连：5_telcom、5_union
     * 华南-广州：5_bgp、5_sbgp
     * 华东-上海一：5_bgp、5_sbgp
     * 华东-上海二：5_bgp、5_sbgp
     * 华北-北京一：5_bgp、5_sbgp
     * 亚太-香港：5_bgp
     * 亚太-曼谷：5_bgp
     * 亚太-新加坡：5_bgp
     * 非洲-约翰内斯堡：5_bgp
     * 西南-贵阳一：5_sbgp
     * 华北-北京四：5_bgp、5_sbgp
     * 拉美-圣地亚哥：5_bgp
     * 拉美-圣保罗一：5_bgp
     * 拉美-墨西哥城一：5_bgp
     * 拉美-布宜诺斯艾利一：5_bgp
     * 拉美-利马一：5_bgp
     * 拉美-圣地亚哥二：5_bgp
     *
     * @return string
     * @author naix
     */
    public function getIpType($region)
    {

        $array = [
            'cn-northeast-1' => ['5_telcom', '5_union'],
            'cn-south-1	' => ['5_bgp', '5_sbgp'],
            'cn-east-3'      => ['5_bgp', '5_sbgp'],
            'cn-east-2'      => ['5_bgp', '5_sbgp'],
            'cn-north-1'     => ['5_bgp', '5_sbgp'],
            'ap-southeast-1' => ['5_bgp'],
            'ap-southeast-2' => ['5_bgp'],
            'ap-southeast-3' => ['5_bgp'],
            'af-south-1'     => ['5_bgp'],
            'cn-southwest-2' => ['5_sbgp'],
            'cn-north-4'     => ['5_bgp', '5_sbgp'],
        ];

        return isset($array[$region]) ? $array[$region][0] : '5_bgp';

    }

}