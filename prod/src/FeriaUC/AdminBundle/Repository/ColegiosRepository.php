<?php

namespace FeriaUC\AdminBundle\Repository;

/**
 * ColegiosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ColegiosRepository extends \Doctrine\ORM\EntityRepository
{
	public function obtenerColegios(){

		$em = $this->getEntityManager();

		$query ="
	        SELECT cole.id as id, cole.nombre as nombre
			FROM AdminBundle:Colegios cole
	   ";

	   $sql = $em->createQuery($query);

	   $oColegios = $sql->getResult();

	   return $oColegios;
	}
}