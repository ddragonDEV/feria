<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContenidoMultimedia
 *
 * @ORM\Table(name="contenido_multimedia")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\ContenidoMultimediaRepository")
 */
class ContenidoMultimedia
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
     * @var EstadosMultimedia
     *
     * @ORM\ManyToOne(targetEntity="EstadosMultimedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estado_multimedia", referencedColumnName="id")
     * })
     */
    private $idEstadoMultimedia;

     /**
     * @var tiposMultimedia
     *
     * @ORM\ManyToOne(targetEntity="TiposMultimedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_multimedia", referencedColumnName="id")
     * })
     */
    private $idTipoMultimedia;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255, nullable=true)
     */
    private $extension;

     /**
     * @var string
     *
     * @ORM\Column(name="codigo_youtube", type="string", length=255, nullable=true)
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
     * @return ContenidoMultimedia
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
     * Set url
     *
     * @param string $url
     *
     * @return ContenidoMultimedia
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return ContenidoMultimedia
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ContenidoMultimedia
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
     * @return ContenidoMultimedia
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
     * Set idEstadoMultimedia
     *
     * @param \FeriaUC\AdminBundle\Entity\EstadosMultimedia $idEstadoMultimedia
     *
     * @return ContenidoMultimedia
     */
    public function setIdEstadoMultimedia(\FeriaUC\AdminBundle\Entity\EstadosMultimedia $idEstadoMultimedia = null)
    {
        $this->idEstadoMultimedia = $idEstadoMultimedia;

        return $this;
    }

    /**
     * Get idEstadoMultimedia
     *
     * @return \FeriaUC\AdminBundle\Entity\EstadosMultimedia
     */
    public function getIdEstadoMultimedia()
    {
        return $this->idEstadoMultimedia;
    }

    /**
     * Set idTipoMultimedia
     *
     * @param \FeriaUC\AdminBundle\Entity\tiposMultimedia $idTipoMultimedia
     *
     * @return ContenidoMultimedia
     */
    public function setIdTipoMultimedia(\FeriaUC\AdminBundle\Entity\tiposMultimedia $idTipoMultimedia = null)
    {
        $this->idTipoMultimedia = $idTipoMultimedia;

        return $this;
    }

    /**
     * Get idTipoMultimedia
     *
     * @return \FeriaUC\AdminBundle\Entity\tiposMultimedia
     */
    public function getIdTipoMultimedia()
    {
        return $this->idTipoMultimedia;
    }

    /**
     * Set codigoYoutube
     *
     * @param string $codigoYoutube
     *
     * @return ContenidoMultimedia
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
}
