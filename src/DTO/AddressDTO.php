<?php

namespace App\DTO;

use App\Entity\Address;
use App\Exceptions\ValidationException;
use App\Interfaces\DTOInterface;
use App\Validator\AddressMatchesCep;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @AddressMatchesCep
 */
class AddressDTO implements DTOInterface
{
    /**
     * @Assert\NotBlank
     */
    private int $cep;

    /**
     * @Assert\NotBlank
     */
    private string $street;

    /**
     * @Assert\NotBlank
     */
    private int $number;

    /**
     * @Assert\NotBlank
     */
    private string $neighborhood;

    /**
     * @Assert\NotBlank
     */
    private string $city;

    /**
     * @Assert\NotBlank
     */
    private string $state;

    private ?string $complement;

    public function __construct(
        int $cep,
        string $street,
        int $number,
        string $neighborhood,
        string $city,
        string $state,
        ?string $complement = null
    ) {
        $this->cep = $cep;
        $this->street = $street;
        $this->number = $number;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->complement = $complement;
    }

    /**
     * @see App\Interfaces\DTOInterface
     */
    public function validate(ValidatorInterface $validator, array $groups): void
    {
        $erros = $validator->validate($this, null, $groups);

        if ($erros->count() > 0) {
            throw new ValidationException('Address contains invalids fields', (array) $erros);
        }
    }

    /**
     * @see App\Interfaces\DTOInterface
     */
    public function ToEntity(?array $options = null): object
    {
        $address = new Address();

        return $address
            ->setCep($this->cep)
            ->setCity($this->city)
            ->setStreet($this->street)
            ->setNumber($this->number)
            ->setNeighborhood($this->neighborhood)
            ->setState($this->state)
            ->setComplement($this->complement);
    }

    /**
     * @deprecated The Address doesn't has a unique identifier like a email or plate
     * @see App\Interfaces\DTOInterface
     */
    public function getIdentifier(): void
    {
    }

    public function getCep(): int
    {
        return $this->cep;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }
}
