<?php

namespace App\Repository;

use App\Entity\DomaineMedical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DomaineMedical>
 *
 * @method DomaineMedical|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomaineMedical|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomaineMedical[]    findAll()
 * @method DomaineMedical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomaineMedicalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomaineMedical::class);
    }

    public function add(DomaineMedical $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DomaineMedical $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


   public function findDomaineOfCabinet($id): array
   {
       return $this->createQueryBuilder('d')
            ->select('d.id, d.libelle')
            ->innerJoin('d.cabinetMedicals','c')
            ->where('c.id = :val')
            ->setParameter('val', $id)
            ->orderBy('d.libelle', 'ASC')
            ->getQuery()
            ->getResult()
        ;
   }


//    public function findOneBySomeField($value): ?DomaineMedical
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
