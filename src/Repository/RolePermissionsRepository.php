<?php

namespace App\Repository;

use App\Entity\RolesPermissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RolePermissionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RolesPermissions::class);
    }



    public function findModified()
    {
        return $this->createQueryBuilder('a')
            ->select('distinct(a.id) as id','IDENTITY(a.role) as id_role','IDENTITY(a.permission) as id_permission','p.name as role','r.name as permission')
            ->innerJoin('a.role','p','WITH','p.id=a.role')
            ->innerJoin('a.permission','r','WITH','r.id=a.permission')
            ->getQuery()
            ->getResult()
            ;

    }

    public function findpermissions($arrOfIds){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('IDENTITY(a.permission) as id_permission')
            ->where($qb->expr()->in('a.role',$arrOfIds))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPermissionId($id_role){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('IDENTITY(a.permission) as id_permission')
            ->where('IDENTITY(a.role) ='.$id_role)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findWithSortWithoutFilter($skip,$take,$field,$dir){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('p.name as role')
            ->distinct(true)
            ->innerJoin('a.role','p','WITH','p.id=a.role')
            ->orderBy('p.' . $field, $dir)
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
            ->Select('COUNT(DISTINCT p.name)')
            ->innerJoin('a.role','p','WITH','p.id=a.role');

        return intval($qb->getQuery()->getSingleScalarResult());
    }


    public function findWithoutSortWithoutFilter($skip,$take){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('p.name as role')
            ->distinct(true)
            ->innerJoin('a.role','p','WITH','p.id=a.role')
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult()
            ;
    }


    public function findWithSortWithFilter($skip, $take, $field,$dir,$filter){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('p.name as role')
            ->distinct(true)
            ->innerJoin('a.role','p','WITH','p.id=a.role')
            ->where($qb->expr()->like('p.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
            ->orderBy('p.' . $field, $dir)
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult()
            ;
    }

    public function FindCountWithFilter($filter)
    {

        $qb = $this->createQueryBuilder('a');

        $qb
            ->select('COUNT(DISTINCT p.name)')
            ->innerJoin('a.role','p','WITH','p.id=a.role')
            ->where($qb->expr()->like('p.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''));

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    public function findWithoutSortWithFilter($skip, $take, $filter){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('p.name as role')
            ->distinct(true)
            ->innerJoin('a.role','p','WITH','p.id=a.role')
            ->where($qb->expr()->like('p.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
            ->setFirstResult($skip)
            ->setMaxResults($take)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findtest1($role){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('r.name as permission')
            ->where($qb->expr()->eq('p.name','\''.$role.'\''))
            ->innerJoin('a.permission','r','WITH','r.id=a.permission')
            ->innerJoin('a.role','p','WITH','p.id=a.role')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPermissionId1($id_role){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('r.id as item_id', 'r.name as item_text')
            ->where('IDENTITY(a.role) ='.$id_role)
            ->innerJoin('a.permission','r','WITH','r.id=a.permission')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findwithIds($role_id,$permission_id){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('a.id as id')
            ->where('a.role ='.$role_id)
            ->andWhere('a.permission ='.$permission_id)
            ->getQuery()
            ->getResult()
            ;
    }

}