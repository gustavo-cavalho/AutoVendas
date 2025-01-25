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

  public function serialize(object $object): string
  {
    return 'TODO: make it show only the fields that are needed';
  }
}