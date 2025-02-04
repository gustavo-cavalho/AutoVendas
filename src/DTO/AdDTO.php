<?php

namespace App\DTO;

use App\Entity\Ad;
use Symfony\Component\Validator\Constraints as Assert;

class AdDTO extends AbstractDTO
{
    /**
     * @Assert\NotBlank
     */
    private int $vehicleId;

    /**
     * @Assert\NotBlank
     */
    private int $vehicleStoreId;

    /**
     * @Assert\Positive
     */
    private float $price;

    /**
     * @Assert\Choice(choices={"ON_SALE", "SOLD"})
     */
    private string $status;
    private string $description;

    public function __construct(
        int $vehicleId,
        int $vehicleStoreId,
        float $price,
        string $status,
        string $description
    ) {
        $this->vehicleId = $vehicleId;
        $this->vehicleStoreId = $vehicleStoreId;
        $this->price = $price;
        $this->status = $status;
        $this->description = $description;
    }

    public function toEntity(): Ad
    {
        $ad = new Ad();

        /*
         * The relations with Vehicle and VehicleStore must be setted
         * in the CrudService while create the entity to save in the db.
         */

        return $ad
            ->setPrice($this->price)
            ->setStatus($this->status)
            ->setDescription($this->description);
    }

    /**
     * @deprecated ad doesn't has a unique value like a plate or email
     */
    public function getIdentifier()
    {
    }

    public function getVehicleId(): int
    {
        return $this->vehicleId;
    }

    public function getVehicleStoreId(): int
    {
        return $this->vehicleStoreId;
    }
}
