<?php

namespace App\Repository;

use App\Entity\Roles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Roles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Roles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Roles[]    findAll()
 * @method Roles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Roles::class);
    }

    public function FindCount()
    {
        $qb = $this->createQueryBuilder('u')
            ->Select('COUNT(u)');

        return intval($qb->getQuery()->getSingleScalarResult());

    }

//    public function findMinId(){
//
//        $qb = $this->createQueryBuilder('a');
//        return $qb
//            ->select('a.id')
//            ->having('a.id = '.$qb->expr()->min('a.id'))
//            ->getQuery()
//            ->getResult()
//            ;
//    }

    public function FindWithoutFilter($skip,$take,$field,$dir)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb
            ->select('u')
            ->orderBy('u.' . $field, $dir)
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult();
    }
    public function FindWithoutFilterAndSort($skip,$take)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb
            ->select('u')
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult();
    }

    public function FindWithFilter($skip, $take, $field,$dir,$filter){

            $qb = $this->createQueryBuilder('u');

            return $qb
                ->select('u')
                ->where($qb->expr()->like('u.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
                ->orderBy('u.' . $field, $dir)
                ->setFirstResult($skip)
                ->setMaxResults($take)
                ->getQuery()
                ->getResult();

    }
    public function FindWithFilter1($skip, $take, $filter){

        $qb = $this->createQueryBuilder('u');

        return $qb
            ->select('u')
            ->where($qb->expr()->like('u.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult();
    }

    public function FindCountWithFilter($filter){

        $qb = $this->createQueryBuilder('u');

        $qb
            ->select('COUNT(u)')
            ->where($qb->expr()->like('u.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''));

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    public function findModified()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id as item_id','a.name as item_text')
            ->getQuery()
            ->getResult()
            ;

    }

    public function findRole($roles_ids){
        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('a.id as item_id','a.name as item_text')
            ->where($qb->expr()->notIn('a.id', $roles_ids))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findMaxId(){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('MAX (a.id)')
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Roles[] Returns an array of Roles objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Roles
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
