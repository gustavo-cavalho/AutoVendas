<?php

namespace App\Interfaces;

interface DTOValidatorInterface
{
  function validate(): ?array;
}