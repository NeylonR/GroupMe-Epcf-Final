<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\PlayerAdvertisement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<PlayerAdvertisement>
 *
 * @method PlayerAdvertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerAdvertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerAdvertisement[]    findAll()
 * @method PlayerAdvertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerAdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerAdvertisement::class);
    }

    public function add(PlayerAdvertisement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PlayerAdvertisement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return PlayerAdvertisement Returns an array of PlayerAdvertisement objects
    */
    public function filterPlayerAdvertisement(Request $request): array
    {
        $query = $this->createQueryBuilder('playerAdvertisement')
        ->select('playerAdvertisement')
        ->orderBy('playerAdvertisement.createdAt', 'DESC')
        ;

        if(!empty($request->get('homeWorld'))){
            $query = $query->andWhere('playerAdvertisement.homeWorld = :homeWorld')
            ->setParameter('homeWorld', $request->get('homeWorld'))
            ;
        }

        if(!empty($request->get('gamingType'))){
            $query = $query->andWhere('playerAdvertisement.playerType = :gamingType')
            ->setParameter('gamingType', $request->get('gamingType'))
            ;
        }

        if(!empty($request->get('language'))){
            $query = $query->andWhere('playerAdvertisement.language = :language')
            ->setParameter('language', $request->get('language'))
            ;
        }

        if(!empty($request->get('ilvl'))){
            $query = $query->andWhere('playerAdvertisement.ilvl >= :ilvl')
            ->setParameter('ilvl', $request->get('ilvl'))
            ;
        }

        if(!empty($request->get('day'))){
            $query = $query->join('playerAdvertisement.day', 'playerAdvertisementDay')
            ->andWhere('playerAdvertisementDay IN (:day)')
            ->setParameter('day', $request->get('day'))
            ;
        }

        if(!empty($request->get('player_advertisement')['job'])){
            $query = $query->join('playerAdvertisement.job', 'playerAdvertisementJob')
            ->andWhere('playerAdvertisementJob IN (:job)')
            ->setParameter('job', $request->get('player_advertisement')['job'])
            ;
        }

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return PlayerAdvertisement[] Returns an array of PlayerAdvertisement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PlayerAdvertisement
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}


        // if(!empty($request->get('activityStart'))){
        //     $query = $query->andWhere('playerAdvertisement.activityStart >= :activityStart')
        //     ->setParameter('activityStart', $request->get('activityStart'))
        //     ;
        //     // ->format('H:i')
        // }

        // if(!empty($request->get('activityEnd'))){
        //     $query = $query->andWhere('playerAdvertisement.activityEnd <= :activityEnd')
        //     ->setParameter('activityEnd', $request->get('activityEnd'))
        //     ;
        //     // ->format('H:i')
        // }