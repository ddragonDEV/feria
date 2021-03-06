<?php

namespace FeriaUC\AdminBundle\Repository;

/**
 * PuntosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PuntosRepository extends \Doctrine\ORM\EntityRepository
{
	public function obtenerPuntos(){

		$em = $this->getEntityManager();

		$query ="
	        SELECT pu.id as id , pu.titulo as titulo, pu.createdAt  as createdAt, pu.updatedAt as updatedAt, ti.id as idTipoPunto, ti.nombre as TipoPunto, es.id as idEstado
			FROM AdminBundle:Puntos pu
			LEFT JOIN AdminBundle:Estados es WITH es.id = pu.idEstado
			LEFT JOIN AdminBundle:TiposPunto ti WITH ti.id = pu.idTipoPunto
			WHERE 1=1 AND pu.idEstado in(1,2)
	   ";

	   $sql = $em->createQuery($query);

	   $aPuntos = $sql->getResult();

	   return $aPuntos;
	}
}
