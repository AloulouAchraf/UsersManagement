<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoRepository")
 */
class Histo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entity;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $row_id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $revtype;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timestamp;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;




    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getRowId()
    {
        return $this->row_id;
    }

    /**
     * @param mixed $row_id
     */
    public function setRowId($row_id)
    {
        $this->row_id = $row_id;
    }

    /**
     * @return mixed
     */
    public function getRevtype()
    {
        return $this->revtype;
    }

    /**
     * @param mixed $revtype
     */
    public function setRevtype($revtype)
    {
        $this->revtype = $revtype;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


}