<?php
namespace App\Manager;
use App\Entity\Histo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Container;

class HistoryManager
{
    protected $em;


    public function __construct(EntityManagerInterface  $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setHistory($name, $rowId,$revType,$time,$user)
    {

        $histo = new Histo();

        $histo->setEntity($name);
        $histo->setRowId($rowId);
        $histo->setRevtype($revType);
        $histo->setTimestamp($time);
        $histo->setUsername($user);



        $this->em->persist($histo);
        $this->em->flush();

        return $histo;

    }

}