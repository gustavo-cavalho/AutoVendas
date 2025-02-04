<?php

namespace App\DTO;

use App\Entity\VehicleStore;
use App\Exceptions\ValidationException;
use App\Validator as Ensure;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Represents a vehicle.
 */
class VehicleStoreDTO extends AbstractDTO
{
    /**
     * @Ensure\ValidCredencial
     */
    private string $credencial;

    /**
     * @Ensure\ValidPhone
     */
    private string $phone;

    /**
     * @Assert\Email
     */
    private string $email;

    /**
     * @Ensure\ValidName
     */
    private string $name;

    private AddressDTO $address;

    public function __construct(
        string $credencial,
        string $phone,
        string $email,
        string $name,
        AddressDTO $address
    ) {
        $this->credencial = $credencial;
        $this->phone = $phone;
        $this->email = $email;
        $this->name = $name;
        $this->address = $address;
    }

    /**
     * Check if the data is valid.
     * !!! NOT IMPLEMENTED YET !!!
     * Is possible to check pass to groups for check
     * ```php
     *   $groups = ['vehicleStore' => ['ALL'], 'address' => ['CEP_ONLY']];
     * ```.
     *
     * @see App\Interfaces\DTOInterface
     */
    public function validate(ValidatorInterface $validator, array $groups): void
    {
        $vehicleStoreGroups = isset($groups['vehicleStore']) ? $groups['vehicleStore'] : [];
        $addressGroups = isset($groups['address']) ? $groups['address'] : [];

        $erros = $validator->validate($this, null, $vehicleStoreGroups);

        if ($erros->count() > 0) {
            throw new ValidationException('The store contains invalid fields', (array) $erros);
        }

        $this->address->validate($validator, $addressGroups);
    }

    /**
     * Convert the DTO to an entity.
     *
     * @example - Doesn't implented yet
     *
     * @see App\Interfaces\DTOInterface
     */
    public function toEntity(): object
    {
        $store = new VehicleStore();

        return $store
            ->setCredencial($this->credencial)
            ->setPhone($this->phone)
            ->setEmail($this->email)
            ->setName($this->name)
            ->setAddress($this->address->ToEntity());
    }

    public function getIdentifier(): string
    {
        return $this->credencial;
    }
}
