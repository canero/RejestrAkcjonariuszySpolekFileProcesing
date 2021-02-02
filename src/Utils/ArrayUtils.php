<?php
namespace App\Utils;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;

class ArrayUtils
{

    /**
     * Function serialize objext to array
     *
     * @param $object to serialize to array
     * @return array of object
     */
    private function serializationToArrayWithoutGroup($object): array
    {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->toArray($object, SerializationContext::create()->enableMaxDepthChecks()->setSerializeNull(true));
    }

    /**
     * Function serialize object to array witch group
     *
     * @param any $object to serialize to array
     * @param array $group of serialization object
     * @return array of object
     */
    public function serializationToArray($object, array $group = null): array
    {
        $serializer = SerializerBuilder::create()->build();
        if (empty($group)) {
            return $this->serializationToArrayWithoutGroup($object);
        }
        return $serializer->toArray($object, SerializationContext::create()->enableMaxDepthChecks()->setSerializeNull(true)->setGroups($group));

    }

    /**
     * check if key exist and is not null
     * @param array array where check key
     * @param srting key to check
     * @return bool
     */
    public function ifKeyExistAndIsNotNull(string $key, array $array):bool
    {
        if (array_key_exists($key, $array)) {
            if (is_string($array[$key])) {
                if (strlen(trim($array[$key])) > 0) {
                    return true;
                }
            } else {
                if (is_null($array[$key]) == false) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * check if key exist and is an array
     *
     * @param string $key
     * @param array $array
     * @return boolean
     */
    public function ifKeyExistAndIsArray(string $key, array $array):bool{
        if (array_key_exists($key, $array)) {
            if( is_array($array[$key])){
                return true;
            }
        }
        return false;
         
    }

    

}
