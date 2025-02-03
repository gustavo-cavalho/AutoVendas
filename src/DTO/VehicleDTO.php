<?php

namespace App\DTO;

use App\Entity\Vehicle;
use App\Validator as Ensure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a vehicle.
 */
class VehicleDTO extends AbstractDTO
{
    /**
     * @Assert\NotNull
     */
    private int $vehicleStoreId;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Length(max=50)
     */
    private string $type;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Length(max=20)
     */
    private string $brand;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Length(max=50)
     */
    private string $model;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Length(max=50)
     */
    private string $manufacturedYear;

    /**
     * @Assert\NotBlank
     *
     * @Assert\GreaterThanOrEqual(0)
     */
    private int $mileage;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Length(max=8)
     *
     * @Ensure\ValidPlate
     */
    private string $licensePlate;

    private int $brandIntegartion;
    private int $modelIntegration;
    private string $yearIntegartion;

    /**
     * @Assert\IsTrue
     */
    public function isValidManufacturedYear(): bool
    {
        return $this->manufacturedYear >= 1900
            && $this->manufacturedYear <= (int) date('Y');
    }

    /**
     * @Assert\IsTrue
     */
    public function isValidaType(): bool
    {
        return $this->type == Vehicle::TYPE_CAR
            || $this->type == Vehicle::TYPE_MOTORCYCLE
            || $this->type == Vehicle::TYPE_TRUCK;
    }

    public function __construct(
        int $vehicleStoreId,
        string $type,
        string $brand,
        string $model,
        string $manufacturedYear,
        int $mileage,
        string $licensePlate
    ) {
        $this->vehicleStoreId = $vehicleStoreId;
        $this->type = $type;
        $this->brand = $brand;
        $this->model = $model;
        $this->manufacturedYear = $manufacturedYear;
        $this->mileage = $mileage;
        $this->licensePlate = $licensePlate;
    }

    /**
     * Convert the DTO to an entity.
     *
     * @example - Doesn't implented yet
     *
     * @see App\Interfaces\DTOInterface
     */
    public function toEntity(): Vehicle
    {
        $vehicle = new Vehicle();

        return $vehicle
            ->setType($this->type)
            ->setBrand($this->brand)
            ->setModel($this->model)
            ->setManufacturedYear($this->manufacturedYear)
            ->setMileage($this->mileage)
            ->setLicensePlate($this->licensePlate);
    }

    public function getIdentifier(): string
    {
        return $this->licensePlate;
    }

    public function getVehicleStoreId(): int
    {
        return $this->vehicleStoreId;
    }

    public function setVehicleStoreId(int $vehicleStoreId): self
    {
        $this->vehicleStoreId = $vehicleStoreId;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getManufacturedYear(): string
    {
        return $this->manufacturedYear;
    }

    public function setManufacturedYear(string $manufacturedYear): self
    {
        $this->manufacturedYear = $manufacturedYear;

        return $this;
    }

    public function getMileage(): int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getLicensePlate(): string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(string $licensePlate): self
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getBrandIntegration(): int
    {
        return $this->brandIntegartion;
    }

    public function setBrandIntegration(int $brandIntegration): self
    {
        $this->brandIntegartion = $brandIntegration;

        return $this;
    }

    public function getModelIntegration(): int
    {
        return $this->modelIntegration;
    }

    public function setModelIntegration(int $modelIntegration): self
    {
        $this->modelIntegration = $modelIntegration;

        return $this;
    }

    public function getYearIntegration(): string
    {
        return $this->yearIntegartion;
    }

    public function setYearIntegration(string $yearIntegration): self
    {
        $this->yearIntegartion = $yearIntegration;

        return $this;
    }
}
