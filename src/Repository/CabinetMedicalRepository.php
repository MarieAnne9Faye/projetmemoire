<?php

namespace App\Repository;

use App\Entity\CabinetMedical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CabinetMedical>
 *
 * @method CabinetMedical|null find($id, $lockMode = null, $lockVersion = null)
 * @method CabinetMedical|null findOneBy(array $criteria, array $orderBy = null)
 * @method CabinetMedical[]    findAll()
 * @method CabinetMedical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CabinetMedicalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CabinetMedical::class);
    }

    public function add(CabinetMedical $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CabinetMedical $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    
    public function findListCabinet($page, $filtre)
    {
         $qb = $this->createQueryBuilder('c')
            ->select("c.id, c.nom, r.libelle as region, d.libelle as departement, c.logo")
            ->innerJoin('c.departement','d')
            ->innerJoin('d.region','r')
            ->setFirstResult(((int)$page - 1) * 10)->setMaxResults(10);

            if ($filtre['region'] != ''){
                $qb->andWhere('r.id = :region')
                   ->setParameter('region', $filtre['region']);
            }

            if ($filtre['departement'] != ''){
                $qb->andWhere('d.id = :dep')
                   ->setParameter('dep',$filtre['departement']);
            }

            if ($filtre['cabinet'] != ''){
                $qb->andWhere('c.id = :nom')
                   ->setParameter('nom',$filtre['cabinet']);
            }
            
            if ($filtre['domaine'] != ''){
                $qb->innerJoin('c.domaineMedical', 'dom')->andWhere('dom.id = :dom')
                   ->setParameter('dom',$filtre['domaine']);
            }

            return $qb->getQuery()->getResult();
    }

    public function countListCabinet($page, $filtre)
    {
         $qb = $this->createQueryBuilder('c')
            ->select("count(c.id)")
            ->innerJoin('c.departement','d')
            ->innerJoin('d.region','r');

            if ($filtre['region'] != ''){
                $qb->andWhere('r.id = :region')
                   ->setParameter('region', $filtre['region']);
            }

            if ($filtre['departement'] != ''){
                $qb->andWhere('d.id = :dep')
                   ->setParameter('dep',$filtre['departement']);
            }

            if ($filtre['cabinet'] != ''){
                $qb->andWhere('c.nom = :nom')
                   ->setParameter('nom',$filtre['cabinet']);
            }
            
            return $qb->getQuery()->getSingleScalarResult();
    }

//    public function findOneBySomeField($value): ?CabinetMedical
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
