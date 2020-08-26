<?php
/**
 * Created by PhpStorm.
 * User: naix
 * Date: 2020/8/20
 * Time: 11:15
 */



use HwCloud\Common\Signer;
use HwCloud\DataInterFace\DataVolume;
use HwCloud\DataInterFace\Eip;
use HwCloud\DataInterFace\SingleDataVolume;
use HwCloud\DataInterFace\SystemVolume;
use HwCloud\EcsClient;


class EcsApi
{


    private $hwKey;
    private $hwSecret;
    private $region;
    public  $client;

    /**
     * EcsApi constructor.
     *
     * @param $region
     */
    public function __construct($region)
    {
        $this->hwKey    = 'xxxxxxxxxxxxxxxx';
        $this->hwSecret = 'xxxxxxxxxxxxxxxx';

        $this->region          = $region;
        $cred                  = new Signer($this->hwKey, $this->hwSecret);
        $this->client          = new EcsClient($cred, $this->region);
    }

    /**
     * @param $projectId
     * @param $instanceId
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function describe($projectId, $instanceId)
    {

        return $this->client->describe($projectId, $instanceId);
    }

    /**
     * @param $projectId
     * @param $instanceId
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function describeDisks($projectId, $instanceId)
    {
        return $this->client->describeDisks($projectId, $instanceId);

    }

    /**
     * @param $projectId
     * @param $jobId
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function jobs($projectId, $jobId)
    {
        return $this->client->jobs($projectId, $jobId);


    }

    /**
     * @param $projectId
     * @param $instanceId
     * @param $flavorRef
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function resize($projectId, $instanceId, $flavorRef)
    {

        $response = $this->client->resize($projectId, $instanceId, $flavorRef);
        

        return $response;
    }

    /**
     * @param       $projectId
     * @param array $instanceIds
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function start($projectId, $instanceIds = [])
    {
        $response = $this->client->start($projectId, $instanceIds);
        
        return $response;
    }

    /**
     * @param        $projectId
     * @param array  $instanceIds
     * @param string $type
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function reStart($projectId, $instanceIds = [], $type = 'SOFT')
    {
        $response = $this->client->reStart($projectId, $instanceIds, $type);
        

        return $response;
    }

    /**
     * @param        $projectId
     * @param        $instanceIds
     * @param string $type
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function stop($projectId, $instanceIds, $type = 'SOFT')
    {

        $response = $this->client->stop($projectId, $instanceIds, $type);
        

        return $response;
    }

    /**
     * @param $projectId
     * @param $instanceIds
     * @param $password
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function resetPass($projectId, $instanceIds, $password)
    {
        $response = $this->client->resetPass($projectId, $instanceIds, $password);
        

        return $response;
    }

    /**
     * @param $projectId
     * @param $instanceIds
     * @param $name
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function reName($projectId, $instanceIds, $name)
    {
        $response = $this->client->reName($projectId, $instanceIds, $name);
        

        return $response;
    }

    /**
     * @param $projectId
     * @param $instanceId
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function vnc($projectId, $instanceId)
    {
        $response = $this->client->vnc($projectId, $instanceId);
        

        return $response;
    }

    /**
     * @param $projectId
     * @param $instanceId
     * @param $adminPass
     * @param $imageid
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function changeOs($projectId, $instanceId, $adminPass, $imageid)
    {
        $response = $this->client->changeos($projectId, $instanceId, $adminPass, $imageid);
        

        return $response;
    }

    /**
     * @param $projectId
     * @param $instanceId
     * @param $volumeId
     *
     * @return array|bool|mixed|void
     * @author naix
     */
    public function attachVolume($projectId, $instanceId,$volumeId ){

        $response = $this->client->attachVolume($projectId, $instanceId,$volumeId );
        

        return $response;
    }

}