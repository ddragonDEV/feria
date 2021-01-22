<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelPuntoMultimedia
 *
 * @ORM\Table(name="rel_punto_multimedia")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\RelPuntoMultimediaRepository")
 */
class RelPuntoMultimedia
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
     * @var InfoPuntos
     *
     * @ORM\ManyToOne(targetEntity="Puntos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_punto", referencedColumnName="id")
     * })
     */
    private $idPunto;

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
     * Set idPunto
     *
     * @param \FeriaUC\AdminBundle\Entity\Puntos $idPunto
     *
     * @return RelPuntoMultimedia
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
     * Set idContenidoMultimedia
     *
     * @param \FeriaUC\AdminBundle\Entity\ContenidoMultimedia $idContenidoMultimedia
     *
     * @return RelPuntoMultimedia
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
