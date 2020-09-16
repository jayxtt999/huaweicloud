<?php
/**
 * Created by PhpStorm.
 * User: naix
 * Date: 2020/8/20
 * Time: 11:18
 */

namespace HwCloud;


class IamClient extends Client
{

    public $domain = 'iam';

    /**
     * 查询区域列表
     * https://support.huaweicloud.com/api-iam/iam_05_0001.html
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function ListRegions()
    {
        $this->version    = 'v3';
        $this->curlMethod = 'GET';
        $this->curlPath = 'regions';

        return $this->request();

    }

    /**
     * 查询指定IAM用户的项目列表
     * https://support.huaweicloud.com/api-iam/iam_06_0002.html
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function Projects()
    {
        $this->version    = 'v3';
        $this->curlMethod = 'GET';
        $this->curlPath = 'projects';

        return $this->request();
    }

    /**
     * 获取IAM用户Token（使用密码）
     * https://apiexplorer.developer.huaweicloud.com/apiexplorer/debug?product=IAM&api=KeystoneCreateUserTokenByPassword
     * @return array|bool|mixed|void
     * @author naix
     */
    public function getToken($auth = [])
    {

        $this->version      = 'v3';
        $this->curlMethod   = 'POST';
        $this->curlParams   = 'auth/tokens';
        $this->noEndpoint   = true;
        $this->returnHeader = true;
        $this->curlData     = [
            'auth' => $auth,
        ];

        return $this->request();
    }

    /**
     * 创建永久访问密钥
     * https://apiexplorer.developer.huaweicloud.com/apiexplorer/doc?product=IAM&api=CreatePermanentAccessKey
     *
     * @param $userId
     * @param $description
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function createPermanentAccessKey($userId, $description)
    {

        $this->version    = 'v3.0';
        $this->curlMethod = 'POST';
        $this->curlPath = 'OS-CREDENTIAL/credentials';
        $this->noEndpoint = true;
        $this->curlData   = [
            'credential' => [
                'user_id'     => $userId,
                'description' => $description,
            ],
        ];

        return $this->request();
    }

    /**
     * 查询指定条件下的项目列表
     * https://support.huaweicloud.com/api-iam/iam_06_0001.html
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function queryProjects($query=[]){

        $this->version    = 'v3';
        $this->curlMethod = 'GET';
        $this->curlPath = 'projects';
        $this->noEndpoint = true;
        $this->curlParams = $query;
        return $this->request();

    }

}