<?php
/**
 * Created by PhpStorm.
 * User: xietaotao
 * Date: 2020/8/20
 * Time: 16:08
 */

namespace HwCloud\DataInterFace;




class DataVolume implements AttributesInterface
{

    public $volumes = [];



    public function setDataVolume(SingleDataVolume $volume){


        $this->volumes[] = [
            'volumetype'  => $volume->volumeType,
            'size'        => $volume->size,
            'multiattach' => $volume->multiattach,
        ];

    }

    public function getArray()
    {
        return $this->volumes;
    }

}