<?php

namespace App\Repository;

use App\Entity\RecruitAdvertisement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<RecruitAdvertisement>
 *
 * @method RecruitAdvertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecruitAdvertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecruitAdvertisement[]    findAll()
 * @method RecruitAdvertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecruitAdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecruitAdvertisement::class);
    }

    public function add(RecruitAdvertisement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RecruitAdvertisement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return RecruitAdvertisement Returns an array of RecruitAdvertisement objects
    */
    public function filterRecruitAdvertisement(Request $request): array
    {
        $query = $this->createQueryBuilder('recruitAdvertisement')
        ->select('recruitAdvertisement')
        ->orderBy('recruitAdvertisement.createdAt', 'DESC')
        ;

        if(!empty($request->get('dataCenter'))){
            $query = $query->andWhere('recruitAdvertisement.dataCenter = :dataCenter')
            ->setParameter('dataCenter', $request->get('dataCenter'))
            ;
        }

        if(!empty($request->get('gamingType'))){
            $query = $query->andWhere('recruitAdvertisement.rosterType = :gamingType')
            ->setParameter('gamingType', $request->get('gamingType'))
            ;
        }

        if(!empty($request->get('language'))){
            $query = $query->andWhere('recruitAdvertisement.language = :language')
            ->setParameter('language', $request->get('language'))
            ;
        }

        if(!empty($request->get('ilvl'))){
            $query = $query->andWhere('recruitAdvertisement.ilvl >= :ilvl')
            ->setParameter('ilvl', $request->get('ilvl'))
            ;
        }

        if(!empty($request->get('day'))){
            $query = $query->join('recruitAdvertisement.day', 'recruitAdvertisementDay')
            ->andWhere('recruitAdvertisementDay IN (:day)')
            ->setParameter('day', $request->get('day'))
            ;
        }

        if(!empty($request->get('recruit_advertisement')['job'])){
            $query = $query->join('recruitAdvertisement.job', 'recruitAdvertisementJob')
            ->andWhere('recruitAdvertisementJob IN (:job)')
            ->setParameter('job', $request->get('recruit_advertisement')['job'])
            ;
        }

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return RecruitAdvertisement[] Returns an array of RecruitAdvertisement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RecruitAdvertisement
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
