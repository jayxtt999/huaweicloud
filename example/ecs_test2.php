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