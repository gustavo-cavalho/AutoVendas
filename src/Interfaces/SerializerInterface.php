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
     * @param object $object to be serialized
     * @param array  $groups the groups to be serialized
     *
     * @return array an associative array of serialized data
     */
    public function serialize(object $object, array $groups = []): array;
}
