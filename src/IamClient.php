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
     * @author xietaotao
     */
    public function ListRegions()
    {
        $this->version    = 'v3';
        $this->curlMethod = 'GET';
        $this->curlParams = 'regions';

        return $this->request();

    }

    /**
     * 查询指定IAM用户的项目列表
     * https://support.huaweicloud.com/api-iam/iam_06_0002.html
     *
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function Projects()
    {
        $this->version    = 'v3';
        $this->curlMethod = 'GET';
        $this->curlParams = 'projects';

        return $this->request();
    }

    /**
     * @return array|bool|mixed|void
     * @author xietaotao
     */
    public function getToken()
    {

        $this->version    = 'v3';
        $this->curlMethod = 'POST';
        $this->curlParams = 'auth/tokens';

        return $this->request();
    }
}