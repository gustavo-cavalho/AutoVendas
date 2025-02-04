<?php

namespace App\Service\Crud;

use App\DTO\VehicleStoreDTO;
use App\Entity\VehicleStore;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Interfaces\DTOInterface;
use App\Repository\VehicleStoreRepository;
use Doctrine\ORM\EntityManagerInterface;

class VehicleStoreService extends AbstractCrudService
{
    protected string $className = VehicleStore::class;
    protected const NOT_FOUND_MESSAGE = 'Can\'t find the store.';
    private VehicleStoreRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->repo = $this->em->getRepository($this->className);
    }

    public function create(DTOInterface $dto): VehicleStore
    {
        $vehicleStore = $this->repo->findByCredencial($dto->getIdentifier());
        if ($vehicleStore) {
            throw new IdentityAlreadyExistsException("A store with the credencial {$dto->getIdentifier()} already exists.");
        }

        $vehicleStore = $dto->ToEntity();

        $this->repo->add($vehicleStore, true);

        return $vehicleStore;
    }

    public function update($existingEntity, DTOInterface $dto): VehicleStore
    {
        $vehicleStore = $this->setNewDataToEntity($dto, $existingEntity);
        $this->em->flush();

        return $vehicleStore;
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
