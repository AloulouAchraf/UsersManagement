<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRolesRepository")
 */
class UsersRoles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Roles")
     * @ORM\JoinColumn(name="Role_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $role;


    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $CreatDate;


    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $ModifDate;

    /**
     * @return mixed
     */
    public function getCreatDate()
    {
        return $this->CreatDate;
    }

    /**
     * @param mixed $CreatDate
     */
    public function setCreatDate($CreatDate): void
    {
        $this->CreatDate = $CreatDate;
    }

    /**
     * @return mixed
     */
    public function getModifDate()
    {
        return $this->ModifDate;
    }

    /**
     * @param mixed $ModifDate
     */
    public function setModifDate($ModifDate): void
    {
        $this->ModifDate = $ModifDate;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }



    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $createdAt;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }


}
