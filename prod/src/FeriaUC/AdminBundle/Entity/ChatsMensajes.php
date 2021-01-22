<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChatsMensajes
 *
 * @ORM\Table(name="chats_mensajes")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\ChatsMensajesRepository")
 */
class ChatsMensajes
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
     * @var Estados
     *
     * @ORM\ManyToOne(targetEntity="Estados")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estado", referencedColumnName="id")
     * })
     */
    private $idEstado;

     /**
     * @var Usuarios
     *
     * @ORM\ManyToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $idUsuario;

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
     * @var array
     *
     * @ORM\Column(name="json_mensajes", type="json_array", nullable=true)
     */
    private $jsonMensajes = [];

    /**
     * @var int
     *
     * @ORM\Column(name="contador_sin_responder", type="integer", nullable=true)
     */
    private $contadorSinResponder = 0;


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
     * Set jsonMensajes
     *
     * @param array $jsonMensajes
     *
     * @return ChatsMensajes
     */
    public function setJsonMensajes($jsonMensajes)
    {
        $this->jsonMensajes = $jsonMensajes;

        return $this;
    }

    /**
     * Get jsonMensajes
     *
     * @return array
     */
    public function getJsonMensajes()
    {
        return $this->jsonMensajes;
    }

    /**
     * Set contadorSinResponder
     *
     * @param integer $contadorSinResponder
     *
     * @return ChatsMensajes
     */
    public function setContadorSinResponder($contadorSinResponder)
    {
        $this->contadorSinResponder = $contadorSinResponder;

        return $this;
    }

    /**
     * Get contadorSinResponder
     *
     * @return integer
     */
    public function getContadorSinResponder()
    {
        return $this->contadorSinResponder;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ChatsMensajes
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
     * @return ChatsMensajes
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
     * Set idEstado
     *
     * @param \FeriaUC\AdminBundle\Entity\Estados $idEstado
     *
     * @return ChatsMensajes
     */
    public function setIdEstado(\FeriaUC\AdminBundle\Entity\Estados $idEstado = null)
    {
        $this->idEstado = $idEstado;

        return $this;
    }

    /**
     * Get idEstado
     *
     * @return \FeriaUC\AdminBundle\Entity\Estados
     */
    public function getIdEstado()
    {
        return $this->idEstado;
    }

    /**
     * Set idUsuario
     *
     * @param \FeriaUC\AdminBundle\Entity\Usuarios $idUsuario
     *
     * @return ChatsMensajes
     */
    public function setIdUsuario(\FeriaUC\AdminBundle\Entity\Usuarios $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \FeriaUC\AdminBundle\Entity\Usuarios
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set idChat
     *
     * @param \FeriaUC\AdminBundle\Entity\Chats $idChat
     *
     * @return ChatsMensajes
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
