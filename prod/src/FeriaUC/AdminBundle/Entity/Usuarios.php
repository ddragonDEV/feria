<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\UsuariosRepository")
 */
class Usuarios implements UserInterface
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
     * @var Regiones
     *
     * @ORM\ManyToOne(targetEntity="Regiones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_region", referencedColumnName="id")
     * })
     */
    private $idRegion;


     /**
     * @var Comunas
     *
     * @ORM\ManyToOne(targetEntity="Comunas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_comuna", referencedColumnName="id")
     * })
     */
    private $idComuna;

     /**
     * @var Visitantes
     *
     * @ORM\ManyToOne(targetEntity="Visitantes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_visitante", referencedColumnName="id")
     * })
     */
    private $idVisitante;

     /**
     * @var Colegios
     *
     * @ORM\ManyToOne(targetEntity="Colegios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_colegio", referencedColumnName="id")
     * })
     */
    private $idColegio;
    

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
     * @ORM\Column(name="rut", type="string", length=255, unique=true)
     */
    private $rut;

     /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

      /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array", nullable=true)
     */
    private $roles = ['ROLE_FERIA_UC_NOVATO'];

    /**
     * @var string
     *
     * @ORM\Column(name="imagen_perfil", type="string", length=255, nullable=true)
     */
    private $imagenPerfil;

     /**
     * @var string
     *
     * @ORM\Column(name="opcion_visitante", type="string", length=255, nullable=true)
     */
    private $opcionVisitante;

    /**
     * @var string
     *
     * @ORM\Column(name="opcion_colegio", type="string", length=255, nullable=true)
     */
    private $opcionColegio;

    /**
     * @var string
     *
     * @ORM\Column(name="ensayo", type="string", length=255,)
     */
    private $ensayo;

    /**
     * @var string
     *
     * @ORM\Column(name="condiciones", type="boolean")
     */
    private $condiciones;

    /**
     * @var string
     *
     * @ORM\Column(name="carreras", type="string", length=255,)
     */
    private $carreras;

    /**
     * @var string
     *
     * @ORM\Column(name="password_md5", type="string", length=255,)
     */
    private $passwordMd5;

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
     * @var string
     *
     * @ORM\Column(name="numero_sesion", type="integer", nullable=true)
     */
    private $numeroSesion = 0;

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
     * @return Usuarios
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
     * @return Usuarios
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
     * Set rut
     *
     * @param string $rut
     *
     * @return Usuarios
     */
    public function setRut($rut)
    {
        $this->rut = $rut;

        return $this;
    }

    /**
     * Get rut
     *
     * @return string
     */
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Usuarios
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get email
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
     * @return Usuarios
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Usuarios
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
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
     * @return Usuarios
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
     * @return Usuarios
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
     * @return Usuarios
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
     * @return Usuarios
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


    /**
     * Set opcionVisitante
     *
     * @param string $opcionVisitante
     *
     * @return Usuarios
     */
    public function setOpcionVisitante($opcionVisitante)
    {
        $this->opcionVisitante = $opcionVisitante;

        return $this;
    }

    /**
     * Get opcionVisitante
     *
     * @return string
     */
    public function getOpcionVisitante()
    {
        return $this->opcionVisitante;
    }

    /**
     * Set opcionColegio
     *
     * @param string $opcionColegio
     *
     * @return Usuarios
     */
    public function setOpcionColegio($opcionColegio)
    {
        $this->opcionColegio = $opcionColegio;

        return $this;
    }

    /**
     * Get opcionColegio
     *
     * @return string
     */
    public function getOpcionColegio()
    {
        return $this->opcionColegio;
    }

    /**
     * Set ensayo
     *
     * @param string $ensayo
     *
     * @return Usuarios
     */
    public function setEnsayo($ensayo)
    {
        $this->ensayo = $ensayo;

        return $this;
    }

    /**
     * Get ensayo
     *
     * @return string
     */
    public function getEnsayo()
    {
        return $this->ensayo;
    }

    /**
     * Set condiciones
     *
     * @param boolean $condiciones
     *
     * @return Usuarios
     */
    public function setCondiciones($condiciones)
    {
        $this->condiciones = $condiciones;

        return $this;
    }

    /**
     * Get condiciones
     *
     * @return boolean
     */
    public function getCondiciones()
    {
        return $this->condiciones;
    }

    /**
     * Set carreras
     *
     * @param string $carreras
     *
     * @return Usuarios
     */
    public function setCarreras($carreras)
    {
        $this->carreras = $carreras;

        return $this;
    }

    /**
     * Get carreras
     *
     * @return string
     */
    public function getCarreras()
    {
        return $this->carreras;
    }

    /**
     * Set idRegion
     *
     * @param \FeriaUC\AdminBundle\Entity\Regiones $idRegion
     *
     * @return Usuarios
     */
    public function setIdRegion(\FeriaUC\AdminBundle\Entity\Regiones $idRegion = null)
    {
        $this->idRegion = $idRegion;

        return $this;
    }

    /**
     * Get idRegion
     *
     * @return \FeriaUC\AdminBundle\Entity\Regiones
     */
    public function getIdRegion()
    {
        return $this->idRegion;
    }

    /**
     * Set idComuna
     *
     * @param \FeriaUC\AdminBundle\Entity\Comunas $idComuna
     *
     * @return Usuarios
     */
    public function setIdComuna(\FeriaUC\AdminBundle\Entity\Comunas $idComuna = null)
    {
        $this->idComuna = $idComuna;

        return $this;
    }

    /**
     * Get idComuna
     *
     * @return \FeriaUC\AdminBundle\Entity\Comunas
     */
    public function getIdComuna()
    {
        return $this->idComuna;
    }

    /**
     * Set idVisitante
     *
     * @param \FeriaUC\AdminBundle\Entity\Visitantes $idVisitante
     *
     * @return Usuarios
     */
    public function setIdVisitante(\FeriaUC\AdminBundle\Entity\Visitantes $idVisitante = null)
    {
        $this->idVisitante = $idVisitante;

        return $this;
    }

    /**
     * Get idVisitante
     *
     * @return \FeriaUC\AdminBundle\Entity\Visitantes
     */
    public function getIdVisitante()
    {
        return $this->idVisitante;
    }

    /**
     * Set idColegio
     *
     * @param \FeriaUC\AdminBundle\Entity\Colegios $idColegio
     *
     * @return Usuarios
     */
    public function setIdColegio(\FeriaUC\AdminBundle\Entity\Colegios $idColegio = null)
    {
        $this->idColegio = $idColegio;

        return $this;
    }

    /**
     * Get idColegio
     *
     * @return \FeriaUC\AdminBundle\Entity\Colegios
     */
    public function getIdColegio()
    {
        return $this->idColegio;
    }

    /**
     * Set passwordMd5
     *
     * @param string $passwordMd5
     *
     * @return Usuarios
     */
    public function setPasswordMd5($passwordMd5)
    {
        $this->passwordMd5 = $passwordMd5;

        return $this;
    }

    /**
     * Get passwordMd5
     *
     * @return string
     */
    public function getPasswordMd5()
    {
        return $this->passwordMd5;
    }

    /**
     * Set numeroSesion
     *
     * @param integer $numeroSesion
     *
     * @return Usuarios
     */
    public function setNumeroSesion($numeroSesion)
    {
        $this->numeroSesion = $numeroSesion;

        return $this;
    }

    /**
     * Get numeroSesion
     *
     * @return integer
     */
    public function getNumeroSesion()
    {
        return $this->numeroSesion;
    }
}
