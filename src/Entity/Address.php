<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * VehicleStore show_store, index_store.
 *
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=VehicleStore::class, inversedBy="address", cascade={"persist", "remove"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"address_store"})
     */
    private $vehicleStore;
    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"store:show","store:index"})
     */
    private $cep;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"store:show"})
     */
    private $street;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"store:show"})
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"store:show"})
     */
    private $neighborhood;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"store:show", "store:index"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"store:show", "store:index"})
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"store:show"})
     */
    private $complement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCep(): ?int
    {
        return $this->cep;
    }

    public function setCep(int $cep): self
    {
        $this->cep = $cep;

        return $this;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(string $neighborhood): self
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getVehicleStore(): ?VehicleStore
    {
        return $this->vehicleStore;
    }

    public function setVehicleStore(VehicleStore $vehicleStore): self
    {
        $this->vehicleStore = $vehicleStore;

        return $this;
    }
}
