<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 */
class Vehicle
{
    public const TYPE_CAR = 'carros';
    public const TYPE_MOTORCYCLE = 'motos';
    public const TYPE_TRUCK = 'caminhoes';

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $manufacturedYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $mileage;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $licensePlate;

    /**
     * @ORM\ManyToOne(targetEntity=VehicleStore::class, inversedBy="vehicleStock")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $vehicleStore;

    /**
     * @ORM\OneToOne(targetEntity=Ad::class, mappedBy="vehicleAdvertised", cascade={"persist", "remove"})
     */
    private $ad;

    /**
     * @ORM\Column(type="integer")
     */
    private $brandIntegration;

    /**
     * @ORM\Column(type="integer")
     */
    private $modelIntegration;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $yearIntegration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getManufacturedYear(): ?string
    {
        return $this->manufacturedYear;
    }

    public function setManufacturedYear(string $manufacturedYear): self
    {
        $this->manufacturedYear = $manufacturedYear;

        return $this;
    }

    public function getMileage(): ?string
    {
        return $this->mileage;
    }

    public function setMileage(string $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(string $licensePlate): self
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getVehicleStore(): ?VehicleStore
    {
        return $this->vehicleStore;
    }

    public function setVehicleStore(?VehicleStore $vehicleStore): self
    {
        $this->vehicleStore = $vehicleStore;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(Ad $ad): self
    {
        // set the owning side of the relation if necessary
        if ($ad->getVehicleAdvertised() !== $this) {
            $ad->setVehicleAdvertised($this);
        }

        $this->ad = $ad;

        return $this;
    }

    public function getBrandIntegration(): ?string
    {
        return $this->brandIntegration;
    }

    public function setBrandIntegration(string $brandIntegration): self
    {
        $this->brandIntegration = $brandIntegration;

        return $this;
    }

    public function getModelIntegration(): ?int
    {
        return $this->modelIntegration;
    }

    public function setModelIntegration(int $modelIntegration): self
    {
        $this->modelIntegration = $modelIntegration;

        return $this;
    }

    public function getYearIntegration(): ?string
    {
        return $this->yearIntegration;
    }

    public function setYearIntegration(string $yearIntegration): self
    {
        $this->yearIntegration = $yearIntegration;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
