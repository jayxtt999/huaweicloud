<?php
/**
 * Created by PhpStorm.
 * User: xietaotao
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


class BssClient extends Client
{
    public $domain = 'bss';


    /**
     * 查询客户包年/包月资源列表
     *
     * @param array  $resourceIds      资源ID列表。  资源ID是指开通资源以后，云服务针对该资源分配的标志，譬如云主机ECS的资源ID是server_id。
     * @param string $orderId          订单号。 查询指定订单下的资源。
     * @param bool   $onlyMainResource 是否只查询主资源，该参数对于请求参数是子资源ID的时候无效，如果resource_ids是子资源ID，只能查询自己。false：查询主资源及附属资源。true
     *                                 只查询主资源。
     * @param int    $offset           偏移量，从0开始。默认值为0。
     * @param int    $limit            每次查询的条数。默认值为10。 这里取500
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function resources($resourceIds = [], $orderId = '', $onlyMainResource = false, $offset = 0, $limit = 500)
    {
        $this->version    = 'v2';
        $this->curlType    = 'POST';
        $this->curlParams = "orders/suscriptions/resources/query";
        $this->noEndpoint = true;

        if ($resourceIds) {
            $this->curlData['resource_ids'] = $resourceIds;
        }

        if ($orderId) {
            $this->curlData['order_id'] = $orderId;
        }
        $this->curlData['only_main_resource'] = $onlyMainResource ? 1 : 0;
        $this->curlData['offset']             = $offset;
        $this->curlData['limit']              = $limit;

        return $this->request();

    }


}