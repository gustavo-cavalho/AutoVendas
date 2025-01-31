<?php

namespace App\Interfaces;

/**
 * Contais methods for serialize objects.
 */
interface SerializerInterface
{
    /**
     * Serialize an object to an array by groups.
     *
     * @param object|array $object to be serialized
     * @param array        $groups the groups to be serialized
     *
     * @return array an associative array of serialized data
     */
    public function serialize($object, array $groups = []): array;
}
