<?php

namespace App\Repository;

use App\Entity\VehicleStore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleStore>
 *
 * @method VehicleStore|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehicleStore|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehicleStore[]    findAll()
 * @method VehicleStore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleStoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleStore::class);
    }

    public function add(VehicleStore $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VehicleStore $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return VehicleStore|null Returns a VehicleStore objects or null
     */
    public function findByCredencial(string $credencial): ?VehicleStore
    {
        return $this->getEntityManager()
             ->createQuery(
                 'SELECT s FROM App\Entity\VehicleStore s
                        WHERE s.credencial = :credencial'
             )
             ->setParameter('credencial', $credencial)
             ->getOneOrNullResult()
        ;
    }

    //    public function findOneBySomeField($value): ?VehicleStore
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
