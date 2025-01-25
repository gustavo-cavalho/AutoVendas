<?php

namespace App\Interfaces;

interface SerializerInterface
{
  public function serialize(object $object): string;
}