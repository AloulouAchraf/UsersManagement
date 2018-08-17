<?php

namespace App\Repository;


use App\Entity\Histo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class HistoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Histo::class);
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

    public function FindCount()
    {
        $qb = $this->createQueryBuilder('u')
            ->Select('COUNT(u)');

        return intval($qb->getQuery()->getSingleScalarResult());

    }





}