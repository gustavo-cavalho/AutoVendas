<?php

namespace App\DTO;

use App\Entity\Address;
use App\Validator\AddressMatchesCep;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @AddressMatchesCep
 */
class AddressDTO extends AbstractDTO
{
    /**
     * must normalize the cep to store only
     * the numbes without the especial chars.
     *
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
        string $cep,
        string $street,
        int $number,
        string $neighborhood,
        string $city,
        string $state,
        ?string $complement = null
    ) {
        $this->cep = $this->normalizeCep($cep);
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
    public function toEntity(?array $options = null): object
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

    private function normalizeCep(string $cepWithEspecialChars): int
    {
        return (int) str_replace(['-', ' '], '', $cepWithEspecialChars);
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
