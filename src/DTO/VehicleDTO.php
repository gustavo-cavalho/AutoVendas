<?php

namespace App\DTO;

use App\Entity\Vehicle;
use App\Interfaces\DTOInterface;

/**
 * Represents a vehicle.
 */
class VehicleDTO implements DTOInterface
{
    /**
     * Check if the data is valid.
     *
     * @see App\Interfaces\DTOInterface
     */
    public function validate(): void
    {
    }

    /**
     * Convert the DTO to an entity.
     *
     * @param array|null $options is possible add extra info if needed
     *
     * @example - Doesn't implented yet
     *
     * @see App\Interfaces\DTOInterface
     */
    public function ToEntity(?array $options = null): object
    {
        return new Vehicle();
    }
}
