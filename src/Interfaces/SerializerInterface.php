<?php

namespace App\Interfaces;

interface SerializerInterface
{
    public function serialize(object $object, array $groups = []): array;
}
