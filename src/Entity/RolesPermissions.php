<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RolePermissionsRepository")
 */
class RolesPermissions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Roles")
     * @ORM\JoinColumn(name="Role_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $role;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Permissions")
     * @ORM\JoinColumn(name="Permission_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $permission;



    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return mixed
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param mixed $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }


}
