<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    public function add(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function mesRv($user, $page): array
    {
        return $this->createQueryBuilder('r')
            ->select("r.id, DATE_FORMAT(r.date, '%d/%m/%Y') as date, r.horaire, d.libelle as domaine, c.nom as cabinet, s.libelle as statut")
            ->innerJoin('r.domaineMedical', 'd')
            ->innerJoin('r.cabinetMedical', 'c')
            ->innerJoin('r.statut', 's')
            ->innerJoin('r.patient', 'p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $user)
            ->orderBy('r.date', 'DESC')
            ->setFirstResult(((int)$page - 1) * 10)->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function CountmesRv($user)
    {
        return $this->createQueryBuilder('r')
            ->select("count(r.id)")
            ->innerJoin('r.domaineMedical', 'd')
            ->innerJoin('r.cabinetMedical', 'c')
            ->innerJoin('r.statut', 's')
            ->innerJoin('r.patient', 'p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function CountmesRvStatut($user, $statut)
    {
        return $this->createQueryBuilder('r')
            ->select("count(r.id)")
            ->innerJoin('r.domaineMedical', 'd')
            ->innerJoin('r.cabinetMedical', 'c')
            ->innerJoin('r.statut', 's')
            ->innerJoin('r.patient', 'p')
            ->andWhere('p.id = :val')
            ->andWhere('s.libelle = :statut')
            ->setParameters(['val'=> $user, 'statut' => $statut])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

//    public function findOneBySomeField($value): ?RendezVous
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
