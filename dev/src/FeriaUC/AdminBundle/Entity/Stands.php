<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stands
 *
 * @ORM\Table(name="stands")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\StandsRepository")
 */
class Stands
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
     * @var Categorias
     *
     * @ORM\ManyToOne(targetEntity="Categorias")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categoria", referencedColumnName="id")
     * })
     */
    private $idCategoria;


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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

     /**
     * @var string
     *
     * @ORM\Column(name="info_general", type="text", nullable=true)
     */
    private $infoGeneral;

     /**
     * @var string
     *
     * @ORM\Column(name="malla_curricular", type="string", length=255, nullable=true)
     */
    private $mallaCurricular;

       /**
     * @var string
     *
     * @ORM\Column(name="imagen_principal", type="string", length=255, nullable=true)
     */
    private $imagenPrincipal;

     /**
     * @var string
     *
     * @ORM\Column(name="url_instagram", type="string", length=255, nullable=true)
     */
    private $urlInstagram;

    /**
     * @var string
     *
     * @ORM\Column(name="url_facebook", type="string", length=255, nullable=true)
     */
    private $urlFacebook;

    /**
     * @var string
     *
     * @ORM\Column(name="url_youtube", type="string", length=255, nullable=true)
     */
    private $urlYoutube;


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
     * @ORM\Column(name="url_web", type="string", length=255, nullable=true)
     */
    private $urlWeb;
    

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
     * @return Stands
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
     * Set infoGeneral
     *
     * @param string $infoGeneral
     *
     * @return Stands
     */
    public function setInfoGeneral($infoGeneral)
    {
        $this->infoGeneral = $infoGeneral;

        return $this;
    }

    /**
     * Get infoGeneral
     *
     * @return string
     */
    public function getInfoGeneral()
    {
        return $this->infoGeneral;
    }

    /**
     * Set mallaCurricular
     *
     * @param string $mallaCurricular
     *
     * @return Stands
     */
    public function setMallaCurricular($mallaCurricular)
    {
        $this->mallaCurricular = $mallaCurricular;

        return $this;
    }

    /**
     * Get mallaCurricular
     *
     * @return string
     */
    public function getMallaCurricular()
    {
        return $this->mallaCurricular;
    }

    /**
     * Set urlInstagram
     *
     * @param string $urlInstagram
     *
     * @return Stands
     */
    public function setUrlInstagram($urlInstagram)
    {
        $this->urlInstagram = $urlInstagram;

        return $this;
    }

    /**
     * Get urlInstagram
     *
     * @return string
     */
    public function getUrlInstagram()
    {
        return $this->urlInstagram;
    }

    /**
     * Set urlFacebook
     *
     * @param string $urlFacebook
     *
     * @return Stands
     */
    public function setUrlFacebook($urlFacebook)
    {
        $this->urlFacebook = $urlFacebook;

        return $this;
    }

    /**
     * Get urlFacebook
     *
     * @return string
     */
    public function getUrlFacebook()
    {
        return $this->urlFacebook;
    }

    /**
     * Set urlYoutube
     *
     * @param string $urlYoutube
     *
     * @return Stands
     */
    public function setUrlYoutube($urlYoutube)
    {
        $this->urlYoutube = $urlYoutube;

        return $this;
    }

    /**
     * Get urlYoutube
     *
     * @return string
     */
    public function getUrlYoutube()
    {
        return $this->urlYoutube;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Stands
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
     * @return Stands
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
     * @return Stands
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
     * @return Stands
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
     * Set idCategoria
     *
     * @param \FeriaUC\AdminBundle\Entity\Categorias $idCategoria
     *
     * @return Stands
     */
    public function setIdCategoria(\FeriaUC\AdminBundle\Entity\Categorias $idCategoria = null)
    {
        $this->idCategoria = $idCategoria;

        return $this;
    }

    /**
     * Get idCategoria
     *
     * @return \FeriaUC\AdminBundle\Entity\Categorias
     */
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    /**
     * Set imagenPrincipal
     *
     * @param string $imagenPrincipal
     *
     * @return Stands
     */
    public function setImagenPrincipal($imagenPrincipal)
    {
        $this->imagenPrincipal = $imagenPrincipal;

        return $this;
    }

    /**
     * Get imagenPrincipal
     *
     * @return string
     */
    public function getImagenPrincipal()
    {
        return $this->imagenPrincipal;
    }

    /**
     * Set urlWeb
     *
     * @param string $urlWeb
     *
     * @return Stands
     */
    public function setUrlWeb($urlWeb)
    {
        $this->urlWeb = $urlWeb;

        return $this;
    }

    /**
     * Get urlWeb
     *
     * @return string
     */
    public function getUrlWeb()
    {
        return $this->urlWeb;
    }
}
