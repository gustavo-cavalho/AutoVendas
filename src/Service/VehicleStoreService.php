<?php

namespace App\Service;

use App\DTO\VehicleStoreDTO;
use App\Entity\VehicleStore;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Interfaces\CrudServiceInterface;
use App\Interfaces\DTOInterface;
use App\Repository\VehicleStoreRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleStoreService implements CrudServiceInterface
{
    private VehicleStoreRepository $repository;

    public function __construct(VehicleStoreRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(DTOInterface $dto): VehicleStore
    {
        $vehicleStore = $this->repository->findByCredencial($dto->getIdentifier());
        if ($vehicleStore) {
            throw new IdentityAlreadyExistsException("A store with the credencial {$dto->getIdentifier()} already exists.");
        }

        $vehicleStore = $dto->ToEntity();

        $this->repository->add($vehicleStore, true);

        return $vehicleStore;
    }

    public function update(int $id, DTOInterface $dto): VehicleStore
    {
        $vehicleStore = $this->repository->find($id);
        if (is_null($vehicleStore)) {
            throw new NotFoundHttpException('Can\'t found this store.');
        }

        $vehicleStore = $this->setNewDataToEntity($dto, $vehicleStore);

        return $vehicleStore;
    }

    public function delete(int $id): void
    {
        $vehicleStore = $this->repository->find($id);
        if (is_null($vehicleStore)) {
            throw new NotFoundHttpException('Can\'t found this store.');
        }

        $this->repository->remove($vehicleStore, true);
    }

    public function find(int $id)
    {
        $store = $this->repository->find($id);
        if (is_null($store)) {
            throw new NotFoundHttpException('Can\t find this store.');
        }

        return $store;
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    private function setNewDataToEntity(VehicleStoreDTO $dto, VehicleStore $existingVehicleStore): VehicleStore
    {
        /**
         * @var VehicleStore $new
         */
        $new = $dto->ToEntity();

        return $existingVehicleStore
            ->setAddress($new->getAddress())
            ->setCredencial($new->getCredencial())
            ->setEmail($new->getEmail())
            ->setName($new->getName())
            ->setPhone($new->getPhone())
            ->setStatus($new->getStatus());
    }
}
