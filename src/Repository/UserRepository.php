<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UsersRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function FindCount()
    {
        $qb = $this->createQueryBuilder('u')
            ->Select('COUNT(u)');

        return intval($qb->getQuery()->getSingleScalarResult());

    }

    public function findMinId(){

        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('a.id')
            ->having('a.id = '.$qb->expr()->min('a.id'))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findModified($MinId)
    {
        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select('a.id as id','a.username as text')
            ->where($qb->expr()->between('a.id',''.$MinId,'62'))
            ->getQuery()
            ->getResult()
            ;

    }

    public function findIdUser($username){

        $qb= $this->createQueryBuilder('a');

        return $qb
            ->select('a.id')
            ->where($qb->expr()->eq('a.username','\''.$username.'\''))
            ->getQuery()
            ->getResult()
            ;
    }



    public function findModified1()
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->select('a.id as id','a.username as text')
            ->getQuery()
            ->getResult()
            ;
    }

    public function FindWithoutFilter($id,$skip,$take,$field,$dir)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb
            ->select('u')
            ->where($qb->expr()->between('u.id', '' . ($id + $skip), '' . ($id + $skip + $take)))
            ->orderBy('u.' . $field, $dir)
            ->getQuery()
            ->getResult();
    }

    public function FindWithFilter($field,$dir,$filter){

        if(count($filter) == 1) {
            $qb = $this->createQueryBuilder('u');

            return $qb
                ->select('u')
                ->where($qb->expr()->like('u.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
                ->orderBy('u.' . $field, $dir)
                ->getQuery()
                ->getResult();
        }
        if(count($filter) == 2) {
            $qb = $this->createQueryBuilder('u');
            return $qb
                ->select('u')
                ->where($qb->expr()->like('u.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
                ->andWhere($qb->expr()->like('u.'. $filter[1]['field'],'\'%'.$filter[1]['value'].'%\''))
                ->orderBy('u.' . $field, $dir)
                ->getQuery()
                ->getResult();
        }
        if(count($filter) == 3) {
            $qb = $this->createQueryBuilder('u');
            return $qb
                ->select('u')
                ->where($qb->expr()->like('u.'. $filter[0]['field'],'\'%'.$filter[0]['value'].'%\''))
                ->andWhere($qb->expr()->like('u.'. $filter[1]['field'],'\'%'.$filter[1]['value'].'%\''))
                ->andWhere($qb->expr()->like('u.'. $filter[2]['field'],'\'%'.$filter[2]['value'].'%\''))
                ->orderBy('u.' . $field, $dir)
                ->getQuery()
                ->getResult();
        }


        }

}