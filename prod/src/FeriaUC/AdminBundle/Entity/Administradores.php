<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Administradores
 *
 * @ORM\Table(name="administradores")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\AdministradoresRepository")
 */
class Administradores implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

      /**
     * @var EstadosUsuarios
     *
     * @ORM\ManyToOne(targetEntity="EstadosUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estado_usuario", referencedColumnName="id")
     * })
     */
    private $idEstadoUsuario;


   /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles = [];

     /**
     * @var string
     *
     * @ORM\Column(name="imagen_perfil", type="string", length=255, nullable=true)
     */
    private $imagenPerfil = 'embajadores.jpg';


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Administradores
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return Administradores
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }


    /**
     * Set username
     *
     * @param string $username
     *
     * @return Administradores
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Administradores
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return Administradores
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

      /**
     * Set imagenPerfil
     *
     * @param string $imagenPerfil
     *
     * @return Administradores
     */
    public function setImagenPerfil($imagenPerfil)
    {
        $this->imagenPerfil = $imagenPerfil;

        return $this;
    }

    /**
     * Get imagenPerfil
     *
     * @return string
     */
    public function getImagenPerfil()
    {
        return $this->imagenPerfil;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Administradores
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Administradores
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set idEstadoUsuario
     *
     * @param \FeriaUC\AdminBundle\Entity\EstadosUsuarios $idEstadoUsuario
     *
     * @return Administradores
     */
    public function setIdEstadoUsuario(\FeriaUC\AdminBundle\Entity\EstadosUsuarios $idEstadoUsuario = null)
    {
        $this->idEstadoUsuario = $idEstadoUsuario;

        return $this;
    }

    /**
     * Get idEstadoUsuario
     *
     * @return \FeriaUC\AdminBundle\Entity\EstadosUsuarios
     */
    public function getIdEstadoUsuario()
    {
        return $this->idEstadoUsuario;
    }


     /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(){

    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(){

    }
}
