<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comunas
 *
 * @ORM\Table(name="comunas")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\ComunasRepository")
 */
class Comunas
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
     * @var Regiones
     *
     * @ORM\ManyToOne(targetEntity="Regiones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_region", referencedColumnName="id")
     * })
     */
    private $idRegion;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;


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
     * @return Comunas
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
     * Set idRegion
     *
     * @param \FeriaUC\AdminBundle\Entity\Regiones $idRegion
     *
     * @return Comunas
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
}
