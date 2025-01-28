<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 */
class Vehicle
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
     * @ORM\Column(type="string", length=20)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $model;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $manufacturedYear;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $mileage;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $fuelType;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $licensePlate;

    /**
     * @ORM\ManyToOne(targetEntity=CarStore::class, inversedBy="vehicleStock")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $carStore;

    /**
     * @ORM\OneToOne(targetEntity=Ad::class, mappedBy="vehicleAdvertised", cascade={"persist", "remove"})
     */
    private $ad;

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

    public function getManufacturedYear(): ?\DateTimeImmutable
    {
        return $this->manufacturedYear;
    }

    public function setManufacturedYear(\DateTimeImmutable $manufacturedYear): self
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

    public function getFuelType(): ?string
    {
        return $this->fuelType;
    }

    public function setFuelType(string $fuelType): self
    {
        $this->fuelType = $fuelType;

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

    public function getCarStore(): ?CarStore
    {
        return $this->carStore;
    }

    public function setCarStore(?CarStore $carStore): self
    {
        $this->carStore = $carStore;

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
}
