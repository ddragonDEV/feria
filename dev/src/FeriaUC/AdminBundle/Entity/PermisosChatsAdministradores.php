<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PermisosChatsAdministradores
 *
 * @ORM\Table(name="permisos_chats_administradores")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\PermisosChatsAdministradoresRepository")
 */
class PermisosChatsAdministradores
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
     * @var Administradores
     *
     * @ORM\ManyToOne(targetEntity="Administradores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_administrador", referencedColumnName="id")
     * })
     */
    private $idAdministrador;

     /**
     * @var Chats
     *
     * @ORM\ManyToOne(targetEntity="Chats")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_chat", referencedColumnName="id")
     * })
     */
    private $idChat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PermisosChatsAdministradores
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
     * Set idAdministrador
     *
     * @param \FeriaUC\AdminBundle\Entity\Administradores $idAdministrador
     *
     * @return PermisosChatsAdministradores
     */
    public function setIdAdministrador(\FeriaUC\AdminBundle\Entity\Administradores $idAdministrador = null)
    {
        $this->idAdministrador = $idAdministrador;

        return $this;
    }

    /**
     * Get idAdministrador
     *
     * @return \FeriaUC\AdminBundle\Entity\Administradores
     */
    public function getIdAdministrador()
    {
        return $this->idAdministrador;
    }

    /**
     * Set idChat
     *
     * @param \FeriaUC\AdminBundle\Entity\Chats $idChat
     *
     * @return PermisosChatsAdministradores
     */
    public function setIdChat(\FeriaUC\AdminBundle\Entity\Chats $idChat = null)
    {
        $this->idChat = $idChat;

        return $this;
    }

    /**
     * Get idChat
     *
     * @return \FeriaUC\AdminBundle\Entity\Chats
     */
    public function getIdChat()
    {
        return $this->idChat;
    }
}
