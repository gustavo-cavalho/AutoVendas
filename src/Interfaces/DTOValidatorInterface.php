<?php

namespace App\Interfaces;

interface DTOValidatorInterface
{
  /**
   * Validade all fields in the object and throw an exception if it fails.
   * That contains the errors that occurred.
   * @throws ValidationException
   */
  function validate(): void;
}