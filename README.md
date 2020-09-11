### huaweicloud
####华为云相关sdk 主要是云服务器部分以及云服务器附加的业务属性
目前相关的有
 - Ecs
 - Evs
 - Iam
 - Ims
 - Vps
 
后续会新增

#### 目前的实现比较简陋（官方没PHP的SDK）
#### 目前的Client的一些参数仅适合自用，，有需要的可以自行改进封装成 Request 对象，同时实现参数限制和验证
#### 目前的的SDK仅发请求，参数验证以及限制条件和错误异常抛出就没实现
#### 目前的curl 比较简陋，建议换成 `guzzlehttp` 
#### 目前还在撸码中



### 示例 1

```php
<?php
require_once __DIR__ . 'autoload.php';

use HwCloud\Common\Signer;
use HwCloud\DataInterFace\DataVolume;
use HwCloud\DataInterFace\Eip;
use HwCloud\DataInterFace\SingleDataVolume;
use HwCloud\DataInterFace\SystemVolume;
use HwCloud\EcsClient;



$hwKey = 'xxxxx';
$hwSecret = 'xxxxx';
$cred = new Signer($hwKey,$hwSecret);

$region = 'cn-south-1';
$client          = new EcsClient($cred, $region);

//获取服务器详情
$result = $client->describe($projectId, $instanceId);
if (!$result || !isset($result['server'])) {
    echo ('huawei request:' . json_encode(['url' => $client->curlUrl, 'params' => $client->curlData]));
    echo  '获取实例信息失败' . $ecsApi->client->getError();exit;
}



```


### 示例 2

```php
<?php


require_once __DIR__ . 'autoload.php';
require_once __DIR__ . 'EcsApi.php';


//封装为EcsApi
$ecsApi = new EcsApi();

$regionId = 'cn-south-1';
//获取服务器详情
$ecsApi          = new EcsApi($regionId);
$result          = $ecsApi->describe($projectId, $instanceId);
if (!$result || !isset($result['server'])) {
    echo ('huawei request:' . json_encode(['url' => $client->curlUrl, 'params' => $client->curlData]));
    echo  '获取实例信息失败' . $ecsApi->client->getError();exit;
}

var_dump($result);

```