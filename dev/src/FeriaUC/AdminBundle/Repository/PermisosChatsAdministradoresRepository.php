<?php

namespace FeriaUC\AdminBundle\Repository;

/**
 * PermisosChatsAdministradoresRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PermisosChatsAdministradoresRepository extends \Doctrine\ORM\EntityRepository
{
	public function obtenerAdministradoresConPermisos($aAdministradores, $idChat){
	    $em = $this->getEntityManager();

		$query ="
	        SELECT perchatadmin.id as idPermiso, ad.id as idAdministrador, ad.nombre as nombreAdministrador, 
	        ad.apellidos as apellidosAdministrador, ad.username as usernameAdministrador, ch.id as idChat
			FROM AdminBundle:PermisosChatsAdministradores perchatadmin
			LEFT JOIN AdminBundle:Administradores ad WITH ad.id = perchatadmin.idAdministrador
			LEFT JOIN AdminBundle:Chats ch WITH ch.id = perchatadmin.idChat
			WHERE 1=1 
			AND perchatadmin.idAdministrador IN (:aAdministradores)
			AND perchatadmin.idChat = :idChat
       ";

		$sql = $em->createQuery($query);
		$sql->setParameter('aAdministradores', $aAdministradores);
		$sql->setParameter('idChat', $idChat);

		$aPermisos = $sql->getResult();

		return $aPermisos;
	}
}
