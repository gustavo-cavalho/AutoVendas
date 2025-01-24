<?php

namespace App\Interfaces;

interface ValueObjectInterface
{
  /**
   * Convert the objects in a format that the entity reconize and if needed
   * do some action.
   * @example -- hashes a password before return it.
   */
  function processToEntity();
  /**
   * Insert the logic to validate a object here.
   */
  function validate(): bool;
}