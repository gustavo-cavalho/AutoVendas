<?php

namespace App\DTO;

use App\Entity\Vehicle;
use App\Entity\VehicleStore;
use App\Exceptions\ValidationException;
use App\Interfaces\DTOInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Represents a vehicle.
 */
class VehicleStoreDTO implements DTOInterface
{
    private string $credencial;
    private string $phone;
    private string $email;
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
     * Is possivle to check pass to groups for check
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
     * @param array|null $options is possible add extra info if needed
     *
     * @example - Doesn't implented yet
     *
     * @see App\Interfaces\DTOInterface
     */
    public function ToEntity(?array $options = null): object
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
        return 'credencial';
    }
}
