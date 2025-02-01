<?php

namespace App\Service;

use App\DTO\VehicleDTO;
use App\Entity\Vehicle;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Interfaces\CrudServiceInterface;
use App\Interfaces\DTOInterface;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleService implements CrudServiceInterface
{
    private VehicleRepository $repository;

    public function __construct(VehicleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(DTOInterface $dto)
    {
        /**
         * @var VehicleDTO $dto
         */
        $vehicle = $this->repository->findByLicensePlate($dto->getIdentifier());
        if (!is_null($vehicle)) {
            throw new IdentityAlreadyExistsException('A vehicle with this plate already exists.');
        }

        $vehicle = $dto->ToEntity();

        $this->repository->add($vehicle, true);

        return $vehicle;
    }

    public function update(int $id, DTOInterface $dto)
    {
        $vehicle = $this->repository->find($id);
        if (is_null($vehicle)) {
            throw new NotFoundHttpException('Cant\'t find the vehicle.');
        }

        $vehicle = $this->setNewDataToEntity($dto, $vehicle);

        return $vehicle;
    }

    public function delete(int $id)
    {
        $vehicle = $this->repository->find($id);
        if (is_null($vehicle)) {
            throw new NotFoundHttpException('Cant\'t find the vehicle.');
        }

        $this->repository->remove($vehicle, true);
    }

    public function find(int $id)
    {
        $vehicle = $this->repository->find($id);
        if (is_null($vehicle)) {
            throw new NotFoundHttpException('Cant\'t find the vehicle.');
        }

        return $vehicle;
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    private function setNewDataToEntity(VehicleDTO $dto, Vehicle $existingVehicle): Vehicle
    {
        /**
         * @var Vehicle $new
         */
        $new = $dto->ToEntity();

        return $existingVehicle
            ->setBrand($new->getBrand())
            ->setModel($new->getModel())
            ->setManufacturedYear($new->getManufacturedYear())
            ->setMileage($new->getMileage())
            ->setLicensePlate($new->getLicensePlate());
    }
}
