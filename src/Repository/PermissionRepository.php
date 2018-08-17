<?php

namespace App\Repository;

use App\Entity\Permissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PermissionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Permissions::class);
    }

    public function FindCount()
    {
        $qb = $this->createQueryBuilder('u')
            ->Select('COUNT(u)');

        return intval($qb->getQuery()->getSingleScalarResult());

    }

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

    public function FindWithFilter($skip, $take, $field, $dir, $filter){

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

    public function findPermissionsNames($arrOfPermissionsIds){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('a.name')
            ->where($qb->expr()->in('a.id',$arrOfPermissionsIds))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findModified()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id as item_id','a.name as item_text')
            ->getQuery()
            ->getResult()
            ;

    }

    public function findPermission($permissions_ids){
        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('a.id as item_id','a.name as item_text')
            ->where($qb->expr()->notIn('a.id', $permissions_ids))
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





}