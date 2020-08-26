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



