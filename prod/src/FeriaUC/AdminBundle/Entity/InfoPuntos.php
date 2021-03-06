<?php

namespace FeriaUC\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoPuntos
 *
 * @ORM\Table(name="info_puntos")
 * @ORM\Entity(repositoryClass="FeriaUC\AdminBundle\Repository\InfoPuntosRepository")
 */
class InfoPuntos
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
     * @ORM\Column(name="info_general", type="text", nullable=true)
     */
    private $infoGeneral;

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
     * Set infoGeneral
     *
     * @param string $infoGeneral
     *
     * @return InfoPuntos
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
     * Set urlInstagram
     *
     * @param string $urlInstagram
     *
     * @return InfoPuntos
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
     * @return InfoPuntos
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
     * @return InfoPuntos
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
     * Set idPunto
     *
     * @param \FeriaUC\AdminBundle\Entity\Puntos $idPunto
     *
     * @return InfoPuntos
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
     * Set urlWeb
     *
     * @param string $urlWeb
     *
     * @return InfoPuntos
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
