<?php

namespace App\Service;

use App\DTO\VehicleDTO;
use App\Entity\Vehicle;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Interfaces\DTOInterface;
use App\Repository\VehicleRepository;
use App\Service\Crud\AbstractCrudService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleService extends AbstractCrudService
{
    protected string $className = Vehicle::class;
    protected const NOT_FOUND_MESSAGE = 'Can\'t find the vehicle.';
    private VehicleRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->repo = $this->em->getRepository($this->className);
    }

    public function create(DTOInterface $dto)
    {
        /**
         * @var VehicleDTO $dto
         */
        $vehicle = $this->repo->findByLicensePlate($dto->getIdentifier());
        if (!is_null($vehicle)) {
            throw new IdentityAlreadyExistsException('A vehicle with this plate already exists.');
        }

        $vehicle = $dto->ToEntity();

        $this->repo->add($vehicle, true);

        return $vehicle;
    }

    public function update(int $id, DTOInterface $dto)
    {
        $vehicle = $this->repo->find($id);
        if (is_null($vehicle)) {
            throw new NotFoundHttpException('Cant\'t find the vehicle.');
        }

        $vehicle = $this->setNewDataToEntity($dto, $vehicle);
        $this->em->flush();

        return $vehicle;
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
