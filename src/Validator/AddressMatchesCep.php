<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION", "CLASS"})
 */
class AddressMatchesCep extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public string $message = "The address doesn't matches with the CEP.";

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
