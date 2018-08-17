<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UsersRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UsersRoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersRoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersRoles[]    findAll()
 * @method UsersRoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRolesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UsersRoles::class);
    }

//    /**
//     * @return UsersRoles[] Returns an array of UsersRoles objects
//     */


    public function findByField()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id as id','IDENTITY(a.user) as id_user','IDENTITY(a.role) as id_role','p.username','r.name as role')
            ->innerJoin('a.user','p','WITH','p.id=a.user')
            ->innerJoin('a.role','r','WITH','r.id=a.role')
//            ->setFirstResult('2')
//            ->setMaxResults('3')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWithSortWithoutFilter($skip,$take,$field,$dir){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('p.username as username')
            ->distinct(true)
            ->innerJoin('a.user','p','WITH','p.id=a.user')
            ->orderBy('p.' . $field, $dir)
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findWithoutSortWithoutFilter($skip,$take){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('p.username as username')
            ->distinct(true)
            ->innerJoin('a.user','p','WITH','p.id=a.user')
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findWithSortWithFilter($skip, $take, $field,$dir,$filter){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('p.username as username')
            ->distinct(true)
            ->innerJoin('a.user','p','WITH','p.id=a.user')
            ->where($qb->expr()->like('p.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
            ->orderBy('p.' . $field, $dir)
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findWithoutSortWithFilter($skip, $take, $filter){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('p.username as username')
            ->distinct(true)
            ->innerJoin('a.user','p','WITH','p.id=a.user')
            ->where($qb->expr()->like('p.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult()
            ;
    }

    public function FindCountWithoutFilter()
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->Select('COUNT(DISTINCT p.username)')
            ->innerJoin('a.user','p','WITH','p.id=a.user');

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    public function FindCountWithFilter($filter)
    {

        $qb = $this->createQueryBuilder('a');

        $qb
            ->select('COUNT(DISTINCT p.username)')
            ->innerJoin('a.user','p','WITH','p.id=a.user')
            ->where($qb->expr()->like('p.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''));

        return intval($qb->getQuery()->getSingleScalarResult());
    }



    public function findtest1($username){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('r.name as role')
            ->where($qb->expr()->eq('p.username','\''.$username.'\''))
            ->innerJoin('a.role','r','WITH','r.id=a.role')
            ->innerJoin('a.user','p','WITH','p.id=a.user')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findroles($id_user){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('IDENTITY(a.role) as id_role')
            ->where($qb->expr()->eq('a.user','\''.$id_user.'\''))
            ->getQuery()
            ->getResult()
            ;
    }





    public function FindCount()
    {
        $qb = $this->createQueryBuilder('u')
            ->Select('COUNT(u)');

        return intval($qb->getQuery()->getSingleScalarResult());

    }

    public function findRoleId($id_user){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('IDENTITY(a.role) as id_role')
            ->where('IDENTITY(a.user) ='.$id_user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findRoleId1($id_user){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('r.id as item_id', 'r.name as item_text')
            ->where('IDENTITY(a.user) ='.$id_user)
            ->innerJoin('a.role','r','WITH','r.id=a.role')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findwithIds($id_user,$id_role){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('a.id as id')
            ->where('a.user ='.$id_user)
            ->andWhere('a.role ='.$id_role)
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?UsersRoles
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
