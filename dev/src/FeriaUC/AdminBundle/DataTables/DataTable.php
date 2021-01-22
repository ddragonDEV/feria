<?php 

namespace FeriaUC\AdminBundle\DataTables;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class DataTable
{
	protected $em;
	private $container;

	public function __construct(EntityManager $entityManager, Container $container)
	{
		$this->em        = $entityManager;
		$this->container = $container;
	}
	//ver el tema de los short
	// aqui hay que detectar la fecha para que busque correctamente, lo dejamos para depsues 
	public function serverProcessingUsuarios($columnas, $start, $length){
		//ver el tema de los short
		$dqlCountFiltered = 'SELECT count(user) FROM AdminBundle:Usuarios user 		
		LEFT JOIN AdminBundle:EstadosUsuarios es WITH es.id = user.idEstadoUsuario
		WHERE 1=1 AND user.idEstadoUsuario in(1,2)';

		$query = 'SELECT user.id as id, user.rut as rut, user.nombre as nombre, user.apellidos as apellidos, user.username as username, user.createdAt as createdAt, user.updatedAt as updatedAt, es.id as idEstadoUsuario
			FROM AdminBundle:Usuarios user
			LEFT JOIN AdminBundle:EstadosUsuarios es WITH es.id = user.idEstadoUsuario
			WHERE 1=1 AND user.idEstadoUsuario in(1,2)';

		$sqlFilter = '';

		if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            // aqui hay que detectar la fecha para que busque correctamente, lo dejamos para depsues 
            $sqlFilter .= "AND user.rut LIKE '%".$strMainSearch."%' OR "
                ."user.nombre LIKE '%".$strMainSearch."%' OR "
                ."user.apellidos LIKE '%".$strMainSearch."%' OR "
                ."user.username LIKE '%".$strMainSearch."%' OR "
                ."user.createdAt  LIKE '%".$strMainSearch."%' OR "
                ."user.updatedAt  LIKE '%".$strMainSearch."%' ";
        }

        if (!empty($sqlFilter)) {
            $query .= $sqlFilter;
            $dqlCountFiltered .= $sqlFilter;
        }

        $query .= 'ORDER BY user.createdAt DESC';

		$items = $this->em
		->createQuery($query)
		->setFirstResult($start)
		->setMaxResults($length)
		->getResult();

		$data = [];
		foreach ($items as $item) {
			$data[] = array(
				"id"                  => $item['id'],
				"idEstado"            => $item['idEstadoUsuario'],
				"rut"                 => $this->rutFormat($item['rut']),
				"nombre_completo"     => $item['nombre'].' '.$item['apellidos'],
				"apellidos"           => $item['apellidos'],
				"email"               => $item['username'],
				"fecha_creacion"      => $item['createdAt']->format('d-m-Y H:i'),
				"fecha_actualizacion" => $item['updatedAt'] ? $item['updatedAt']->format('d-m-Y H:i'):'',
				"acciones"            => null
			);
		}

		$recordsTotal = $this->em
			->createQuery('SELECT count(user) FROM AdminBundle:Usuarios user 		
		                   LEFT JOIN AdminBundle:EstadosUsuarios es WITH es.id = user.idEstadoUsuario
		                   WHERE 1=1 AND user.idEstadoUsuario in(1,2)')
			->getSingleScalarResult();

		 $recordsFiltered = $this->em
			->createQuery($dqlCountFiltered)
			->getSingleScalarResult();

		return array(
			'draw'             => 0,
			'recordsTotal'     => $recordsTotal,
			'recordsFiltered'  => $recordsFiltered, //por ahora
			'data'             => $data,
			'dql'              => $query,
			'dqlCountFiltered' => $dqlCountFiltered,
		);
	}

	public function serverProcessingPuntos($columnas, $start, $length){
		//ver el tema de los short
		$dqlCountFiltered = 'SELECT count(pu) FROM AdminBundle:Puntos pu		
		LEFT JOIN AdminBundle:TiposPunto ti WITH ti.id = pu.idTipoPunto
		WHERE 1=1 AND pu.idEstado in(1,2)';
		
		$query = 'SELECT pu.id as id , pu.titulo as titulo, pu.createdAt  as createdAt, pu.updatedAt as updatedAt, ti.id as idTipoPunto, ti.nombre as tipoPunto, es.id as idEstado
			FROM AdminBundle:Puntos pu
			LEFT JOIN AdminBundle:Estados es WITH es.id = pu.idEstado
			LEFT JOIN AdminBundle:TiposPunto ti WITH ti.id = pu.idTipoPunto
			WHERE 1=1 AND pu.idEstado in(1,2)';

		$sqlFilter = '';

		if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            // aqui hay que detectar la fecha para que busque correctamente, lo dejamos para depsues 
            $sqlFilter .= "AND pu.titulo LIKE '%".$strMainSearch."%' OR "
                ."ti.nombre LIKE '%".$strMainSearch."%' OR "
                ."pu.createdAt LIKE '%".$strMainSearch."%' OR "
                ."pu.updatedAt LIKE '%".$strMainSearch."%'";
        }

        if (!empty($sqlFilter)) {
            $query .= $sqlFilter;
            $dqlCountFiltered .= $sqlFilter;
        }

        $query .= 'ORDER BY pu.createdAt DESC';

		$items = $this->em
		->createQuery($query)
		->setFirstResult($start)
		->setMaxResults($length)
		->getResult();

		$data = [];
		foreach ($items as $item) {
			$data[] = array(
				"id"                  => $item['id'],
				"idEstado"            => $item['idEstado'],
				"idTipoPunto"         => $item['idTipoPunto'],
				"titulo"              => $item['titulo'],
				"tipo_punto"          => $item['tipoPunto'],
				"fecha_creacion"      => $item['createdAt']->format('d-m-Y H:i'),
				"fecha_actualizacion" => $item['updatedAt'] ? $item['updatedAt']->format('d-m-Y H:i'):'',
				"acciones"            => null
			);
		}

		$recordsTotal = $this->em
			->createQuery('SELECT count(pu) FROM AdminBundle:Puntos pu		
							LEFT JOIN AdminBundle:TiposPunto ti WITH ti.id = pu.idTipoPunto
							WHERE 1=1 AND pu.idEstado in(1,2)')
			->getSingleScalarResult();

		 $recordsFiltered = $this->em
			->createQuery($dqlCountFiltered)
			->getSingleScalarResult();

		return array(
			'draw'             => 0,
			'recordsTotal'     => $recordsTotal,
			'recordsFiltered'  => $recordsFiltered, //por ahora
			'data'             => $data,
			'dql'              => $query,
			'dqlCountFiltered' => $dqlCountFiltered,
		);
	}

	public function serverProcessingStands($columnas, $start, $length, $id_punto){
		$dqlCountFiltered = 'SELECT count(st) FROM AdminBundle:Stands st
			LEFT JOIN AdminBundle:Estados es WITH es.id = st.idEstado
			LEFT JOIN AdminBundle:Puntos pu WITH pu.id = st.idPunto
			LEFT JOIN AdminBundle:Categorias cat WITH cat.id = st.idCategoria
			WHERE 1=1 
			AND st.idEstado in(1,2)';

		$query = 'SELECT st.id as id, st.nombre as nombre, st.createdAt  as createdAt, st.updatedAt as updatedAt, 
	        pu.titulo as punto, cat.nombre as categoria, es.id as idEstado
			FROM AdminBundle:Stands st
			LEFT JOIN AdminBundle:Estados es WITH es.id = st.idEstado
			LEFT JOIN AdminBundle:Puntos pu WITH pu.id = st.idPunto
			LEFT JOIN AdminBundle:Categorias cat WITH cat.id = st.idCategoria
			WHERE 1=1 
			AND st.idEstado in(1,2)';

		$sqlFilter = 'AND pu.id ='.$id_punto;
		
		if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];
            
            $sqlFilter .= "AND (st.nombre  LIKE '%".$strMainSearch."%' OR "
                ."pu.titulo LIKE '%".$strMainSearch."%' OR "
                ."cat.nombre LIKE '%".$strMainSearch."%' OR "
                ."st.createdAt LIKE '%".$strMainSearch."%' OR "
                ."st.updatedAt LIKE '%".$strMainSearch."%') ";
        }

        if (!empty($sqlFilter)) {
            $query .= $sqlFilter;
            $dqlCountFiltered .= $sqlFilter;
        }

        $query .= " ORDER BY st.nombre  ASC";

		$items = $this->em
		->createQuery($query)
		->setFirstResult($start)
		->setMaxResults($length)
		->getResult();

		$data = [];
		foreach ($items as $item) {
			$data[] = array(
				"id"                  => $item['id'],
				"idEstado"            => $item['idEstado'],
				"nombre"              => $item['nombre'],
				"punto"               => $item['punto'],
				"categoria"           => $item['categoria'],
				"fecha_creacion"      => $item['createdAt']->format('d-m-Y H:i'),
				"fecha_actualizacion" => $item['updatedAt'] ? $item['updatedAt']->format('d-m-Y H:i'):'',
				"acciones"            => null
			);
		}

		$auxCount ='';
		if(!empty($idPuntoLimpio)){
			$auxCount.= 'AND punt.id ='.$idPuntoLimpio;
		}
		$recordsTotal = $this->em
			->createQuery('SELECT count(st) FROM AdminBundle:Stands st
			LEFT JOIN AdminBundle:Estados es WITH es.id = st.idEstado
			LEFT JOIN AdminBundle:Puntos pu WITH pu.id = st.idPunto
			LEFT JOIN AdminBundle:Categorias cat WITH cat.id = st.idCategoria
			WHERE 1=1 
			AND st.idEstado in(1,2)'.$auxCount)
			->getSingleScalarResult();

		 $recordsFiltered = $this->em
			->createQuery($dqlCountFiltered)
			->getSingleScalarResult();

		return array(
			'draw'             => 0,
			'recordsTotal'     => $recordsTotal,
			'recordsFiltered'  => $recordsFiltered, //por ahora
			'data'             => $data,
			'dql'              => $query,
			'dqlCountFiltered' => $dqlCountFiltered,
		);
	}

	public function serverProcessingCharlas($columnas, $start, $length){
		//ver el tema de los short
		$dqlCountFiltered = 'SELECT count(ch) FROM AdminBundle:Charlas ch
			LEFT JOIN AdminBundle:Estados es WITH es.id = ch.idEstado
			LEFT JOIN AdminBundle:Stands st  WITH  st.id = ch.idStand 
			WHERE 1=1 AND ch.idEstado in(1,2)';
		
		$query = 'SELECT ch.id as id, ch.titulo as titulo, ch.expositor as expositor, ch.fecha as fecha,  
		    es.id as idEstado, st.nombre as carrera, ch.createdAt as createdAt, ch.updatedAt as updatedAt
			FROM AdminBundle:Charlas ch
			LEFT JOIN AdminBundle:Estados es WITH es.id = ch.idEstado
			LEFT JOIN AdminBundle:Stands st  WITH  st.id = ch.idStand 
			WHERE 1=1 AND ch.idEstado in(1,2)';

		$sqlFilter = '';

		if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            // aqui hay que detectar la fecha para que busque correctamente, lo dejamos para depsues 
            $sqlFilter .= "AND ch.titulo LIKE '%".$strMainSearch."%' OR "
                ."ch.expositor LIKE '%".$strMainSearch."%' OR "
                ."ch.fecha LIKE '%".$strMainSearch."%' OR "
                ."st.nombre LIKE '%".$strMainSearch."%' OR "
                ."ch.createdAt LIKE '%".$strMainSearch."%' OR "
                ."ch.updatedAt LIKE '%".$strMainSearch."%'";
        }

        if (!empty($sqlFilter)) {
            $query .= $sqlFilter;
            $dqlCountFiltered .= $sqlFilter;
        }

        $query .= 'ORDER BY ch.createdAt DESC';

		$items = $this->em
		->createQuery($query)
		->setFirstResult($start)
		->setMaxResults($length)
		->getResult();

		$data = [];
		foreach ($items as $item) {
			$data[] = array(
				"id"                  => $item['id'],
				"idEstado"            => $item['idEstado'],
				"titulo"              => $item['titulo'],
				"expositor"           => $item['expositor'],
				"carrera"             => $item['carrera'],
				"fecha"               => $item['fecha']->format('d-m-Y H:i'),
				"fecha_creacion"      => $item['createdAt']->format('d-m-Y H:i'),
				"fecha_actualizacion" => $item['updatedAt'] ? $item['updatedAt']->format('d-m-Y H:i'):'',
				"acciones"            => null
			);
		}

		$recordsTotal = $this->em
			->createQuery('SELECT count(ch) FROM AdminBundle:Charlas ch
			LEFT JOIN AdminBundle:Estados es WITH es.id = ch.idEstado
			LEFT JOIN AdminBundle:Stands st  WITH  st.id = ch.idStand 
			WHERE 1=1 AND ch.idEstado in(1,2)')
			->getSingleScalarResult();

		 $recordsFiltered = $this->em
			->createQuery($dqlCountFiltered)
			->getSingleScalarResult();

		return array(
			'draw'             => 0,
			'recordsTotal'     => $recordsTotal,
			'recordsFiltered'  => $recordsFiltered, //por ahora
			'data'             => $data,
			'dql'              => $query,
			'dqlCountFiltered' => $dqlCountFiltered,
		);
	}

	private function rutFormat($rut) {
     return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
    }
}