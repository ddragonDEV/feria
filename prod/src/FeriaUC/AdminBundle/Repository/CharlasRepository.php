<?php

namespace FeriaUC\AdminBundle\Repository;

/**
 * CharlasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CharlasRepository extends \Doctrine\ORM\EntityRepository
{
	public function obtenerCharlas(){

	    $em = $this->getEntityManager();

	  	$query ="
	        SELECT ch.id as id, ch.expositor as expositor, ch.fecha as fecha, ch.codigoYoutube as codigoYoutube, es.id as idEstado,
	        ch.createdAt as createdAt, ch.updatedAt as updatedAt
			FROM AdminBundle:Charlas ch
			LEFT JOIN AdminBundle:Estados es WITH es.id = ch.idEstado 
			WHERE 1=1 AND ch.idEstado in(1,2)
	   ";

	   $sql = $em->createQuery($query);
	  
	   $aCharlas = $sql->getResult();

	   return $aCharlas;        
    }

    public function obtenerCharlasPorStand($id_stand=null){

    	$em = $this->getEntityManager();

    	$query ="
	        SELECT ch.id as id, ch.titulo as titulo, ch.expositor as expositor, ch.lugar as lugar, ch.fecha as fecha,
	        ch.codigoYoutube as codigo_youtube, sta.nombre as stand, ch.fechaTermino as fechaTermino
			FROM AdminBundle:Charlas ch
			LEFT JOIN AdminBundle:Estados es WITH es.id = ch.idEstado
			LEFT JOIN AdminBundle:Stands sta WITH sta.id = ch.idStand 
			WHERE 1=1 
			AND ch.idEstado in(1)
			ORDER BY ch.fecha ASC
	   ";
	   if($id_stand){
   
	         $query ="
		        SELECT ch.id as id, ch.titulo as titulo, ch.expositor as expositor, ch.lugar as lugar, ch.fecha as fecha, 
		        ch.codigoYoutube as codigo_youtube, sta.nombre as stand, ch.fechaTermino as fechaTermino
				FROM AdminBundle:Charlas ch
				LEFT JOIN AdminBundle:Estados es WITH es.id = ch.idEstado
				LEFT JOIN AdminBundle:Stands sta WITH sta.id = ch.idStand 
				WHERE 1=1 
				AND ch.idEstado in(1)
				AND sta.id = :id_stand
				ORDER BY ch.fecha  ASC
		   ";
	   }

	   $sql = $em->createQuery($query);

	   if($id_stand){
          $sql->setParameter('id_stand', $id_stand);
	   }
	   
	   $aCharlas = $sql->getResult();

	   return $aCharlas;
    }
}
