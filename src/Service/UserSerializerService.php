<?php

namespace App\Service;

use App\Interfaces\SerializerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

/**
 * Service responsible for serialize objects.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.0
 */
class UserSerializerService implements SerializerInterface
{
    private SymfonySerializer $serializer;

    public function __construct(SymfonySerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Serialize an object to an array by groups.
     *
     * @param object $object to be serialized
     * @param array  $groups the groups to be serialized
     *
     * @return array an associative array of serialized data
     *
     * @see App\Interfaces\SerializerInterface
     */
    public function serialize(object $object, array $groups = []): array
    {
        $context = [];
        if (!empty($groups)) {
            $context['groups'] = $groups;
        }

        $serializedDara = $this->serializer->serialize($object, 'json', $context);

        return json_decode($serializedDara, true);
    }
}
