<?php

namespace FeriaUC\AdminBundle\Repository;

/**
 * RelPuntoMultimediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RelPuntoMultimediaRepository extends \Doctrine\ORM\EntityRepository
{
	public function obtenerMultimediasPuntos($id_punto){
		$em = $this->getEntityManager();
		
		$query ="
	        SELECT contenidoMul.id as idContenidoMultimedia,  contenidoMul.nombre as nombre, contenidoMul.url as url, 
	        contenidoMul.codigoYoutube as codigoYoutube, contenidoMul.createdAt as createdAt, contenidoMul.updatedAt as updatedAt, tipoMul.nombre as tipoMultimedia,estadoMul.id as idEstadoMultimedia
			FROM AdminBundle:relPuntoMultimedia relPuntoMult
			LEFT JOIN AdminBundle:Puntos pun WITH pun.id = relPuntoMult.idPunto
			LEFT JOIN AdminBundle:ContenidoMultimedia contenidoMul WITH contenidoMul.id = relPuntoMult.idContenidoMultimedia
			LEFT JOIN AdminBundle:EstadosMultimedia estadoMul WITH estadoMul.id = contenidoMul.idEstadoMultimedia
			LEFT JOIN AdminBundle:tiposMultimedia tipoMul WITH tipoMul.id = contenidoMul.idTipoMultimedia
			WHERE 1=1 
			AND contenidoMul.idEstadoMultimedia in(1,2)
			AND pun.id = :id_punto
       ";

		$sql = $em->createQuery($query);
		$sql->setParameter('id_punto', $id_punto);

		$aRelPuntoMultimedia  = $sql->getResult();

		return $aRelPuntoMultimedia;
	}
}