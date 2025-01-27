<?php

namespace App\Service;

use App\Interfaces\SerializerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class UserSerializerService implements SerializerInterface
{
    private SymfonySerializer $serializer;

    public function __construct(SymfonySerializer $serializer)
    {
        $this->serializer = $serializer;
    }

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
