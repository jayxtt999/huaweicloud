<?php
/**
 * Created by PhpStorm.
 * User: naix
 * Date: 2020/8/20
 * Time: 16:08
 */

namespace HwCloud\DataInterFace;


class SystemVolume extends SingleDataVolume implements AttributesInterface
{

    public function getArray()
    {
        $volume = [
            'volumetype' => $this->volumeType,
        ];
        if($this->size){
            $volume['size'] = $this->size;
        }
        return $volume;
    }
}