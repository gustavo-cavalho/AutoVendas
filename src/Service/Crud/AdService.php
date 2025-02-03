<?php

namespace App\Service\Crud;

use App\DTO\AdDTO;
use App\Entity\Ad;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Interfaces\DTOInterface;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdService extends AbstractCrudService
{
    protected string $className = Ad::class;
    protected const NOT_FOUND_MESSAGE = 'Can\'t find the Ad.';
    private AdRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->repo = $em->getRepository(Ad::class);
    }

    public function create(DTOInterface $dto)
    {
        $ad = $this->repo->findByVehicle($dto->getIdentifier());
        if (!is_null($ad)) {
            throw new IdentityAlreadyExistsException('This vehicle already has an advertisement.');
        }

        $ad = $dto->toEntity();

        $this->repo->add($ad, true);

        return $ad;
    }

    public function update(int $id, DTOInterface $dto)
    {
        $ad = $this->repo->find($id);
        if (is_null($ad)) {
            throw new NotFoundHttpException('Can\'t find the Ad.');
        }

        $ad = $this->setNewDataToEntity($dto, $ad);
        $this->em->flush();

        return $ad;
    }

    private function setNewDataToEntity(AdDTO $adDto, Ad $existingAd): Ad
    {
        $new = $adDto->toEntity();

        return $existingAd
            ->setDescription($new->getDescription())
            ->setPrice($new->getPrice())
            ->setStatus($new->getStatus())
            ->setUpdatedAt(new \DateTimeImmutable());
    }
}
