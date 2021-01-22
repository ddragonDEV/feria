<?php

namespace FeriaUC\AdminBundle\Repository;

/**
 * AdministradoresRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdministradoresRepository extends \Doctrine\ORM\EntityRepository
{
	public function obtenerAdministradores(){

	$em = $this->getEntityManager();

	$query ="
        SELECT admin.id as id, admin.nombre as nombre, admin.apellidos as apellidos, admin.username as username, admin.roles as roles, admin.createdAt as createdAt, admin.updatedAt as updatedAt, es.id as idEstadoUsuario
		FROM AdminBundle:Administradores admin
		LEFT JOIN AdminBundle:EstadosUsuarios es WITH es.id = admin.idEstadoUsuario
		WHERE 1=1 AND admin.idEstadoUsuario in(1,2)
   ";

   $sql = $em->createQuery($query);

   $aAdministradores = $sql->getResult();

   return $aAdministradores;
	}
}