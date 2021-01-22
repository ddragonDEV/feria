<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Puntos
 *
 * @ORM\Table(name="puntos")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\PuntosRepository")
 */
class Puntos
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
     * @var TiposPunto
     *
     * @ORM\ManyToOne(targetEntity="TiposPunto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_punto", referencedColumnName="id")
     * })
     */
    private $idTipoPunto;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

     /**
     * @var string
     *
     * @ORM\Column(name="sub_titulo", type="string", length=255)
     */
    private $subTitulo;

      /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

      /**
     * @var string
     *
     * @ORM\Column(name="valor_left", type="string", length=255)
     */
    private $left;

      /**
     * @var string
     *
     * @ORM\Column(name="top", type="string", length=255)
     */
    private $top;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

      /**
     * @var string
     *
     * @ORM\Column(name="imagen_principal", type="string", length=255, nullable=true)
     */
    private $imagenPrincipal;

      /**
     * @var string
     *
     * @ORM\Column(name="menu", type="integer", nullable=true)
     */
    private $menu = 0;

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
     * @ORM\Column(name="icono", type="string", length=255, nullable=true)
     */
    private $icono;


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
     * @return Puntos
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Puntos
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
     * @return Puntos
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
     * @return Puntos
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
     * Set idTipoPunto
     *
     * @param \FeriaUC\AdminBundle\Entity\TiposPunto $idTipoPunto
     *
     * @return Puntos
     */
    public function setIdTipoPunto(\FeriaUC\AdminBundle\Entity\TiposPunto $idTipoPunto = null)
    {
        $this->idTipoPunto = $idTipoPunto;

        return $this;
    }

    /**
     * Get idTipoPunto
     *
     * @return \FeriaUC\AdminBundle\Entity\TiposPunto
     */
    public function getIdTipoPunto()
    {
        return $this->idTipoPunto;
    }

    /**
     * Set subTitulo
     *
     * @param string $subTitulo
     *
     * @return Puntos
     */
    public function setSubTitulo($subTitulo)
    {
        $this->subTitulo = $subTitulo;

        return $this;
    }

    /**
     * Get subTitulo
     *
     * @return string
     */
    public function getSubTitulo()
    {
        return $this->subTitulo;
    }


    /**
     * Set top
     *
     * @param string $top
     *
     * @return Puntos
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return string
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set imagenPrincipal
     *
     * @param string $imagenPrincipal
     *
     * @return Puntos
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
     * Set menu
     *
     * @param boolean $menu
     *
     * @return Puntos
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return boolean
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Puntos
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set left
     *
     * @param string $left
     *
     * @return Puntos
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * Get left
     *
     * @return string
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set icono
     *
     * @param string $icono
     *
     * @return Puntos
     */
    public function setIcono($icono)
    {
        $this->icono = $icono;

        return $this;
    }

    /**
     * Get icono
     *
     * @return string
     */
    public function getIcono()
    {
        return $this->icono;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Puntos
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
