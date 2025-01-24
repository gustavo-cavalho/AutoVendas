<?php

namespace App\Interfaces;

interface DTOConverterInterface
{
  function ToEntity(?array $roles=null): object;
}