<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Charlas
 *
 * @ORM\Table(name="charlas")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\CharlasRepository")
 */
class Charlas
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
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="expositor", type="string", length=255)
     */
    private $expositor;

      /**
     * @var string
     *
     * @ORM\Column(name="lugar", type="string", length=255)
     */
    private $lugar;

    /**
     * @var \Date
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_youtube", type="string", length=255)
     */
    private $codigoYoutube;

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
     * @var \Date
     *
     * @ORM\Column(name="fecha_termino", type="datetime", nullable=true)
     */
    private $fechaTermino;


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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Charlas
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }


        /**
     * Set expositor
     *
     * @param string $expositor
     *
     * @return Charlas
     */
    public function setExpositor($expositor)
    {
        $this->expositor = $expositor;

        return $this;
    }

    /**
     * Get expositor
     *
     * @return string
     */
    public function getExpositor()
    {
        return $this->expositor;
    }

    /**
     * Set lugar
     *
     * @param string $lugar
     *
     * @return Charlas
     */
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;

        return $this;
    }

    /**
     * Get lugar
     *
     * @return string
     */
    public function getLugar()
    {
        return $this->lugar;
    }


    /**
     * Set fecha
     *
     * @param \datetime $fecha
     *
     * @return Charlas
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \datetime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoYoutube
     *
     * @param string $codigoYoutube
     *
     * @return Charlas
     */
    public function setCodigoYoutube($codigoYoutube)
    {
        $this->codigoYoutube = $codigoYoutube;

        return $this;
    }

    /**
     * Get codigoYoutube
     *
     * @return string
     */
    public function getCodigoYoutube()
    {
        return $this->codigoYoutube;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Charlas
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
     * @return Charlas
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
     * @return Charlas
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
     * @return Charlas
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
     * @return Charlas
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

    /**
     * Set fechaTermino
     *
     * @param \DateTime $fechaTermino
     *
     * @return Charlas
     */
    public function setFechaTermino($fechaTermino)
    {
        $this->fechaTermino = $fechaTermino;

        return $this;
    }

    /**
     * Get fechaTermino
     *
     * @return \DateTime
     */
    public function getFechaTermino()
    {
        return $this->fechaTermino;
    }
}
