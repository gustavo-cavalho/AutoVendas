<?php

namespace App\Service;

use App\DTO\VehicleStoreDTO;
use App\Entity\VehicleStore;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Interfaces\DTOInterface;
use App\Repository\VehicleStoreRepository;
use App\Service\Crud\AbstractCrudService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function update(int $id, DTOInterface $dto): VehicleStore
    {
        $vehicleStore = $this->repo->find($id);
        if (is_null($vehicleStore)) {
            throw new NotFoundHttpException('Can\'t found this store.');
        }

        $vehicleStore = $this->setNewDataToEntity($dto, $vehicleStore);
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
