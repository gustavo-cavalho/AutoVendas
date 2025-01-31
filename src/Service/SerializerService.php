<?php

namespace App\Service;

use App\Interfaces\SerializerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

/**
 * Service responsible for serialize objects.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.1
 */
class SerializerService implements SerializerInterface
{
    private SymfonySerializer $serializer;
    private string $className;

    public function __construct(SymfonySerializer $serializer, string $className)
    {
        $this->serializer = $serializer;
        $this->className = $className;
    }

    /**
     * Serialize an object to an array by groups.
     *
     * @param object|array $object to be serialized
     * @param array        $groups the groups to be serialized
     *
     * @return array an associative array of serialized data
     *
     * @see App\Interfaces\SerializerInterface
     */
    public function serialize($object, array $groups = []): array
    {
        $context = [];
        if (!empty($groups)) {
            $context['groups'] = $groups;
        }

        $serializedDara = $this->serializer->serialize($object, 'json', $context);

        return json_decode($serializedDara, true);
    }

    /**
     * Turns the json into a Entity.
     *
     * @param $data the information that the entity must contain
     *
     * @return T the entity type
     *
     * @see App\Interfaces\SerializerInterface
     */
    public function deserialize($data, array $groups)
    {
        $context = [];
        if (!empty($groups)) {
            $context['groups'] = $groups;
        }

        return $this->serializer->deserialize($data, $this->className, 'json', $groups);
    }
}
