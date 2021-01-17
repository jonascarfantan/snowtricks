<?php
namespace App\_Core\Trait ;

trait Manager {
    /**
     * @param $object
     *
     * @return array
     */
    public static function toArray($object) {
        return json_decode(json_encode($object), true);
    }
}
