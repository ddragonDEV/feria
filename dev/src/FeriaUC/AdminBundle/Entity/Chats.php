<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chats
 *
 * @ORM\Table(name="chats")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\ChatsRepository")
 */
class Chats
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
     * @var Puntos
     *
     * @ORM\ManyToOne(targetEntity="Puntos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_punto", referencedColumnName="id")
     * })
     */
    private $idPunto;

     /**
     * @var Stands
     *
     * @ORM\ManyToOne(targetEntity="Stands")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_stand", referencedColumnName="id")
     * })
     */
    private $idStand;

     /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="dias_funcionamiento", type="string", length=255)
     */
    private $diasFuncionamiento;


    /**
     * @var \time
     *
     * @ORM\Column(name="hora_apertura", type="time")
     */
    private $horaApertura;

    /**
     * @var \time
     *
     * @ORM\Column(name="hora_cierre", type="time")
     */
    private $horaCierre;

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
     * @return Chats
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
     * Set diasFuncionamiento
     *
     * @param string $diasFuncionamiento
     *
     * @return Chats
     */
    public function setDiasFuncionamiento($diasFuncionamiento)
    {
        $this->diasFuncionamiento = $diasFuncionamiento;

        return $this;
    }

    /**
     * Get diasFuncionamiento
     *
     * @return string
     */
    public function getDiasFuncionamiento()
    {
        return $this->diasFuncionamiento;
    }

    /**
     * Set horaApertura
     *
     * @param \DateTime $horaApertura
     *
     * @return Chats
     */
    public function setHoraApertura($horaApertura)
    {
        $this->horaApertura = $horaApertura;

        return $this;
    }

    /**
     * Get horaApertura
     *
     * @return \DateTime
     */
    public function getHoraApertura()
    {
        return $this->horaApertura;
    }

    /**
     * Set horaCierre
     *
     * @param \DateTime $horaCierre
     *
     * @return Chats
     */
    public function setHoraCierre($horaCierre)
    {
        $this->horaCierre = $horaCierre;

        return $this;
    }

    /**
     * Get horaCierre
     *
     * @return \DateTime
     */
    public function getHoraCierre()
    {
        return $this->horaCierre;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Chats
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
     * @return Chats
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
     * @return Chats
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
     * Set idPunto
     *
     * @param \FeriaUC\AdminBundle\Entity\Puntos $idPunto
     *
     * @return Chats
     */
    public function setIdPunto(\FeriaUC\AdminBundle\Entity\Puntos $idPunto = null)
    {
        $this->idPunto = $idPunto;

        return $this;
    }

    /**
     * Get idPunto
     *
     * @return \FeriaUC\AdminBundle\Entity\Puntos
     */
    public function getIdPunto()
    {
        return $this->idPunto;
    }

    /**
     * Set idStand
     *
     * @param \FeriaUC\AdminBundle\Entity\Stands $idStand
     *
     * @return Chats
     */
    public function setIdStand(\FeriaUC\AdminBundle\Entity\Stands $idStand = null)
    {
        $this->idStand = $idStand;

        return $this;
    }

    /**
     * Get idStand
     *
     * @return \FeriaUC\AdminBundle\Entity\Stands
     */
    public function getIdStand()
    {
        return $this->idStand;
    }
}
