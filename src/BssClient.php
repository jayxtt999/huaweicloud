<?php
/**
 * Created by PhpStorm.
 * User: naix
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


use HwCloud\Common\Signer;

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
     * @author naix
     */
    public function resources($resourceIds = [], $orderId = '', $onlyMainResource = false, $offset = 0, $limit = 500)
    {
        $this->version    = 'v2';
        $this->curlMethod = 'POST';
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

    /**
     * @param $orderId
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function orderDetail($orderId)
    {

        $this->version    = 'v2';
        $this->curlParams = "orders/customer-orders/details/" . $orderId;
        $this->noEndpoint = true;

        return $this->request();

    }

    /**
     * https://support.huaweicloud.com/api-oce/api_order_00018.html
     * @param array $resourceIds
     * @param       $periodType
     * @param       $periodNum
     * @param       $expirePolicy
     * @param       $isAutoPay
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function renew($resourceIds, $periodType, $periodNum, $expirePolicy, $isAutoPay)
    {

        $this->version    = 'v2';
        $this->curlMethod = 'POST';
        $this->curlParams = "orders/subscriptions/resources/renew";
        $this->noEndpoint = true;

        $this->curlData = [
            'resource_ids'  => $resourceIds,
            'period_type'   => $periodType,
            'period_num'    => $periodNum,
            'expire_policy' => $expirePolicy,
            'is_auto_pay'   => $isAutoPay,
        ];

        return $this->request();
    }


    /**
     * 校验客户注册信息
     *   客户注册时可检查客户的登录名称、手机号或者邮箱是否可以用于注册。
     *   登录名称不能同名。
     *   注意事项：
     *       该接口只允许使用合作伙伴AK/SK或者Token调用。
     *
     * @param $type  该字段内容可填为：“email”、“mobile”或“name”。
     * @param $value 手机号、邮箱或登录名称。
     *               手机号 需符合正则表达式 ^\d{4}-\d+$；包括国家码，以00开头，格式：00XX-XXXXXXXX。目前手机号仅支持以86开头的国家码。
     *               name
     *               需符合正则表达式^([a-zA-Z-]([a-zA-Z0-9_-]){4,31})$，长度5-32；不能以“op_”或“shadow_”开头且不能全为数字，且只能以字母（不区分大小写）或者-开头。
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function checkUser($type, $value)
    {

        $this->version    = 'v2';
        $this->curlMethod = 'POST';
        $this->curlParams = "partners/sub-customers/users/check-identity";
        $this->noEndpoint = true;

        $this->curlData = [
            'search_type'  => $type,
            'search_value' => $value,
        ];

        return $this->request();

    }


    /**
     * 创建客户
     *
     * @param $accountId   伙伴销售平台的用户唯一标识，该标识的具体值由伙伴分配。
     * @param $accountType 华为分给合作伙伴的平台标识。
     * @param $domainName  客户的华为云账号名。 如果为空，随机生成。 不能以“op_”或“shadow_”开头且不能全为数字。
     *                     校验长度（5到32位）和规则^([a-zA-Z_-]([a-zA-Z0-9_-])*)$。
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function createUser($accountId, $accountType, $domainName)
    {
        $this->version    = 'v2';
        $this->curlMethod = 'POST';
        $this->curlParams = "partners/sub-customers";
        $this->noEndpoint = true;

        $this->curlData = [
            'xaccount_id'        => $accountId,
            'xaccount_type'      => $accountType,
            'domain_name'        => $domainName,
            'is_close_market_ms' => true,
        ];

        return $this->request();

    }

    /**
     * 查询客户列表
     * https://support.huaweicloud.com/api-bpconsole/mc_00021.html
     *
     * @param array $query
     *
     * @author naix
     */
    public function queryUser($query = [])
    {

        $this->version    = 'v2';
        $this->curlMethod = 'POST';
        $this->curlParams = "partners/sub-customers/query";
        $this->noEndpoint = true;
        if ($query) {
            $this->curlData = $query;
        }

        return $this->request();

    }


    /**
     * 实名
     *
     * @param       $customerId      客户账号ID
     * @param       $identifyType    认证方案：  0：个人证件认证 4：个人银行卡认证。这种方式下，仅仅需要上传一张个人扫脸的图片附件即可。
     * @param array $verifiedFileUrl 个人证件认证时证件附件的文件URL，该URL地址必须按照顺序填写。
     *                               以身份证举例，譬如身份证人像面文件名称是abc023，国徽面是def004，个人手持身份证人像面是gh007，那么这个地方需要按照
     *                               abc023
     *                               def004
     *                               gh007
     *                               的顺序填写URL（文件名称区分大小写）。
     *                               证件附件目前仅仅支持jpg、jpeg、bmp、png、gif、pdf格式，单个文件最大不超过10M。
     *                               个人银行卡认证时直接上传一张个人扫脸的图片附件即可。
     *                               这个URL是相对URL，不需要包含桶名和download目录，只要包含download目录下的子目录和对应文件名称即可。举例如下：
     *                               如果上传的证件附件在桶中的位置是：
     *                               https://bucketname.obs.Endpoint.myhuaweicloud.com/download/abc023.jpg，该字段填写abc023.jpg；
     *                               如果上传的证件附件在桶中的位置是：https://bucketname.obs.Endpoint.myhuaweicloud.com/download/test/abc023.jpg，该字段填写test/abc023.jpg。
     * @param       $name            姓名
     * @param       $verifiedNumber  证件号码。
     * @param       $xaccountType    华为分给合作伙伴的平台标识。
     *                               该标识的具体值由华为分配。
     * @param       $verifiedType    证件类型：
     *                               0：身份证，上传的附件为3张，第1张是身份证人像面，第2张是身份证国徽面，第3张是个人手持身份证人像面；
     *                               3：护照，上传的附件为3张，第1张是护照个人资料页，第2张是，护照入境盖章页，第3张是手持护照个人资料页；
     *                               5：港澳通行证，上传的附件为3张，第1张是港澳居民来往内地通行证正面（人像面），第2张是港澳居民来往内地通行证反面，第3张是手持港澳居民来往内地通行证人像面；
     *                               6：台湾通行证，上传的附件为3张，第1张是台湾居民来往大陆通行证正面（人像面），第2张是台湾居民来往大陆通行证反面，第3张是手持台湾居民来往大陆通行证人像面；
     *                               7：海外驾照，上传的附件为2张，第1张是中国以外驾照正面照片（人像面），第2张是手持中国以外驾照人像面照片；
     *                               9：港澳居民居住证，上传的附件为3张，第1张是港澳居民居住证人像面，第2张是，港澳居民居住证国徽面，第3张是手持港澳居民居住证人像面照片；
     *                               10：台湾居民居住证，上传的附件为3张，第1张是台湾居民居住证人像面，第2张是台湾居民居住证国徽面，第3张是手持台湾居民居住证人像面照片。
     *                               当identifyType=0的时候，该字段需要填写，否则忽略该字段的取值。
     *
     * @param       $extraParam      额外参数，具体参见文档
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function realNameAuth($customerId, $identifyType, $name, $verifiedNumber, $xAccountType, $verifiedType, $verifiedFileUrl = [], $extraParam = [])
    {

        $this->version    = 'v2';
        $this->curlMethod = 'POST';
        $this->curlParams = "customers/realname-auths/individual";
        $this->noEndpoint = true;
        $this->curlData   = [
            'customer_id'       => $customerId,
            'identify_type'     => $identifyType,
            'name'              => $name,
            'verified_number'   => $verifiedNumber,
            'xaccount_type'     => $xAccountType,
            'verified_type'     => $verifiedType,
            //'verified_file_url' => [],
        ];

        if ($extraParam) {
            $this->curlData = array_merge($this->curlData, $extraParam);
        }

        return $this->request();

    }

    /**
     * 2.2.4 查询实名认证审核结果
     *
     * @param $customerId 客户账号ID
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function realNameAuthResult($customerId)
    {

        $this->version    = 'v2';
        $this->curlMethod = 'GET';
        $this->curlParams = "customers/realname-auths/result?customer_id=" . $customerId;
        $this->noEndpoint = true;

        return $this->request();

    }


    /**
     * 设置客户信用额度
     *
     * @param $customerId 客户账号ID
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */

    /**
     * 设置客户信用额度
     * @param $customerId 客户账号ID
     * @param $adjType 调整类型 1：增加 2：减少
     * @param $amount 调整的虚拟额度，单位为元。 取值大于0且精确到小数点后2位
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function setCreditCoupons($customerId, $adjType, $amount)
    {

        $this->version    = 'v2';
        $this->curlMethod = 'PUT';
        $this->curlParams = "partners/sub-customers/credit-coupons";
        $this->noEndpoint = true;
        $this->curlData   = [
            'customer_id' => $customerId,
            'adj_type'    => $adjType,
            'amount'      => $amount,
        ];

        return $this->request();

    }

    /**
     * 查询客户信用额度
     *
     * @param array $customerIds 客户ID列表，最大支持100个 如果其中有存在不是该伙伴的客户ID，在响应中不返回该客户信息。
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function queryCreditCoupons($customerIds=[]){
        $this->version    = 'v2';
        $this->curlMethod = 'POST';
        $this->curlParams = "partners/sub-customers/credit-coupons/query";
        $this->noEndpoint = true;
        $this->curlData   = [
            'customer_ids' => $customerIds,
        ];

        return $this->request();
    }

}