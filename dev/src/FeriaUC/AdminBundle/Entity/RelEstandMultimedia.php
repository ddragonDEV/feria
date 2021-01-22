<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelEstandMultimedia
 *
 * @ORM\Table(name="rel_estand_multimedia")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\RelEstandMultimediaRepository")
 */
class RelEstandMultimedia
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
     * @var Stands
     *
     * @ORM\ManyToOne(targetEntity="Stands")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_stand", referencedColumnName="id")
     * })
     */
    private $idStand;

    /**
     * @var ContenidoMultimedia
     *
     * @ORM\ManyToOne(targetEntity="ContenidoMultimedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_contenido_multimedia", referencedColumnName="id")
     * })
     */
    private $idContenidoMultimedia;



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
     * Set idStand
     *
     * @param \FeriaUC\AdminBundle\Entity\Stands $idStand
     *
     * @return RelEstandMultimedia
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
     * Set idContenidoMultimedia
     *
     * @param \FeriaUC\AdminBundle\Entity\ContenidoMultimedia $idContenidoMultimedia
     *
     * @return RelEstandMultimedia
     */
    public function setIdContenidoMultimedia(\FeriaUC\AdminBundle\Entity\ContenidoMultimedia $idContenidoMultimedia = null)
    {
        $this->idContenidoMultimedia = $idContenidoMultimedia;

        return $this;
    }

    /**
     * Get idContenidoMultimedia
     *
     * @return \FeriaUC\AdminBundle\Entity\ContenidoMultimedia
     */
    public function getIdContenidoMultimedia()
    {
        return $this->idContenidoMultimedia;
    }
}
