<?php

namespace App\Traits\Util;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * Trait SerializerUtil.
 *
 * This trait provides a helpful method for serializing objects using the SerializerInterface.
 * It must be implemented in a class that has access to the `SerializerInterface` service.
 * The `serialize` method converts an object to a JSON associative array, with optional serialization groups.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.0
 */
trait SerializerUtil
{
    /**
     * Serializes an object into a JSON associative array.
     *
     * This method uses the injected `serializer` service to serialize an object into JSON format,
     * and then decodes the JSON into an associative array. You can specify serialization groups
     * to customize the attributes included in the serialization.
     *
     * @param object       $object the object to be serialized
     * @param string|array $groups (optional) An array of serialization groups to customize the output
     *
     * @return array the serialized object as an associative array
     */
    public function serialize($object, $groups = []): array
    {
        if (!$this->serializer instanceof SerializerInterface) {
            throw new \LogicException('Serializer is not injected or is not an instance of SerializerInterface.');
        }

        $json = $this->serializer->serialize($object, 'json', ['groups' => $groups]);

        return json_decode($json, true);
    }
}
