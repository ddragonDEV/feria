<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FeriaUC\AdminBundle\Entity\Charlas;

class CharlasController extends Controller
{
	/**
	 * @Route("/admin/charlas", options={"expose"=true}, name="admin_charlas")
	*/
	public function indexAction(){

		$em = $this->getDoctrine()->getManager();

		//$aCharlas = $em->getRepository('AdminBundle:Charlas')->obtenerCharlas();

		return $this->render('AdminBundle:Charlas:index.html.twig');
	}

	/**
     * @Route("/admin/charlas/datatables", options={"expose"=true}, name="admin_charlas_datatables")
    */
    public function datatablesAction(Request $request){

    	$em = $this->getDoctrine()->getManager();

		$start  = $request->query->get('start');
		$length = $request->query->get('length');
		$columns = $request->query->get('columns');
		
		$dataTable = $this->container->get('datatable_server_side')->serverProcessingCharlas($columns, $start, $length);

		return new JsonResponse($dataTable);
    }

	/**
	 * @Route("/admin/charlas/agregar", options={"expose"=true}, name="admin_charlas_agregar")
	*/
	public function agregarAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		$oStands = $em->getRepository('AdminBundle:Stands')->findAll();

		if ($request->getMethod() === 'POST') {

		  date_default_timezone_set("America/Santiago");

		  $dateHoy  = date("Y-m-d H:i:s");
		  $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));


		  $titulo    = $request->request->get('titulo');
		  $expositor = $request->request->get('expositor');
		  $lugar     = $request->request->get('lugar');

		  $fecha = $request->request->get('fecha');
		  $oFecha = new \DateTime(date('Y-m-d H:i', strtotime($fecha)));

		  $fecha_termino = $request->request->get('fecha_termino');
		  $oFechaTermino = new \DateTime(date('Y-m-d H:i', strtotime($fecha_termino)));

		  $codigoYoutube = $request->request->get('codigoYoutube');
		  $id_stand = $request->request->get('id_stand');

		  if($titulo  && $expositor &&  $lugar  && $fecha &&  $fecha_termino && $codigoYoutube){

		  	$oCharlas = new Charlas();
            
            $oCharlas->setTitulo($titulo);
		  	$oCharlas->setExpositor($expositor);
		  	$oCharlas->setLugar($lugar);
		  	$oCharlas->setFecha($oFecha);
		  	$oCharlas->setFechaTermino($oFechaTermino);
		  	$oCharlas->setCodigoYoutube($codigoYoutube);
		  	$oCharlas->setIdEstado($em->getReference('AdminBundle:Estados', 1));

		  	if($id_stand){
              $oCharlas->setIdStand($em->getReference('AdminBundle:Stands', $id_stand));

              $oStands =  $em->getRepository('AdminBundle:Stands')->find($id_stand);
              $oPuntos = $em->getRepository('AdminBundle:Puntos')->find($oStands->getIdPunto()->getId());

              $oCharlas->setIdPunto($oPuntos);
		  	}

		  	$oCharlas->setCreatedAt($oDateHoy);

		  	try {
				$em->persist($oCharlas);
				$em->flush();

				$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
				return new JsonResponse($arrRespuesta);

			 } catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
				$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
				return new JsonResponse($arrRespuesta);
			 }

		  }

		  $errores = array(
		  	   "titulo"            => ($titulo)? "":"El nombre es obligatorio",
			   "expositor"         => ($expositor)? "":"El expositor es obligatorio",
			   "lugar"            =>  ($lugar)? "":"La reseña es obligatoria",
			   "fecha"             => ($fecha)? "":"La fecha es obligatoria",
			   "fecha_termino"     => ($fecha_termino)? "":"La fecha de termino es obligatoria",
			   "codigoYoutube"     => ($codigoYoutube)? "":"El código youtube es obligatorio",
		  );

		  $valores = array(
		  	"titulo"             => $titulo,
		    "expositor"          => $expositor,
		    "lugar"              => $lugar,
			"fecha"              => $fecha,
			"fecha_termino"      => $fecha_termino,
			"codigoYoutube"      => $codigoYoutube,
            "id_stand"           => $id_stand
		   );

		   return $this->render('AdminBundle:Charlas:agregar.html.twig',
				array(
					"errores"      => $errores,
					"valores"      => $valores,
					"oStands"      => $oStands,
				)
			);
		}

		$errores = array(
		   "titulo"                     => "",	
		   "expositor"                  => "",
		   "lugar"                      => "",
		   "fecha"                      => "",
		   "fecha_termino"              => "",
		   "codigoYoutube"              => "",
		);

		$valores = array(
		   "titulo"                     => "",	
		   "expositor"                  => "",
		   "lugar"                      => "",
			"fecha"                     => "",
			"fecha_termino"             => "",
			"codigoYoutube"             => "",
            "id_stand"                  => "",
		);

		return $this->render('AdminBundle:Charlas:agregar.html.twig',
			array(
				"errores"     => $errores,
				"valores"     => $valores,
				"oStands"     => $oStands,
			)
		);
	}

	/**
	 * @Route("/admin/charlas/editar", options={"expose"=true}, name="admin_charlas_editar")
	*/
	public function editarAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			$oStands = $em->getRepository('AdminBundle:Stands')->findAll();

			if ($request->getMethod() === 'POST') {

			  date_default_timezone_set("America/Santiago");

			  $dateHoy  = date("Y-m-d H:i:s");
			  $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			  $id_charla = $request->request->get('id_charla');
			  $oCharlas  = $em->getRepository('AdminBundle:Charlas')->find($id_charla);

			  $titulo    = $request->request->get('titulo');
			  $expositor = $request->request->get('expositor');
			  $lugar     = $request->request->get('lugar');

			  $fecha = $request->request->get('fecha');
			  $oFecha = new \DateTime(date('Y-m-d H:i', strtotime($fecha)));

			  $fecha_termino = $request->request->get('fecha_termino');
		      $oFechaTermino = new \DateTime(date('Y-m-d H:i', strtotime($fecha_termino)));

			  $codigoYoutube = $request->request->get('codigoYoutube');
			  $id_stand      = $request->request->get('id_stand');

			   if($titulo  && $expositor &&  $lugar  && $fecha &&  $fecha_termino && $codigoYoutube){

			  	$oCharlas->setTitulo($titulo);
			  	$oCharlas->setExpositor($expositor);
			  	$oCharlas->setLugar($lugar);
			  	$oCharlas->setFecha($oFecha);
			  	$oCharlas->setFechaTermino($oFechaTermino);
			  	$oCharlas->setCodigoYoutube($codigoYoutube);
			  	$oCharlas->setIdEstado($em->getReference('AdminBundle:Estados', 1));
			  	$oCharlas->setIdStand(null);
			  	$oCharlas->setIdPunto(null);

			  	if($id_stand){
	              $oCharlas->setIdStand($em->getReference('AdminBundle:Stands', $id_stand));

	               $oStands =  $em->getRepository('AdminBundle:Stands')->find($id_stand);
	               $oPuntos = $em->getRepository('AdminBundle:Puntos')->find($oStands->getIdPunto()->getId());

	               $oCharlas->setIdPunto($oPuntos);
			  	}

			  	$oCharlas->setUpdatedAt($oDateHoy);

			  	try {
					$em->persist($oCharlas);
					$em->flush();

					$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
					return new JsonResponse($arrRespuesta);

				 } catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
					$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
					return new JsonResponse($arrRespuesta);
				 }

			  }

			  $errores = array(
				   "titulo"            => ($titulo)? "":"El nombre es obligatorio",
				   "expositor"         => ($expositor)? "":"El expositor es obligatorio",
				   "lugar"             =>  ($lugar)? "":"La reseña es obligatoria",
				   "fecha"             => ($fecha)? "":"La fecha es obligatoria",
				   "fecha_termino"     => ($fecha_termino)? "":"La fecha de termino es obligatoria",
				   "codigoYoutube"     => ($codigoYoutube)? "":"El código youtube es obligatorio",
			  );

			  $valores = array(
			    "titulo"             => $titulo,
			    "expositor"          => $expositor,
			    "lugar"              => $lugar,
				"fecha"              => $fecha,
				"fecha_termino"      => $fecha_termino,
				"codigoYoutube"      => $codigoYoutube,
	            "id_stand"           => $id_stand,
	            "id_charla"          => $id_charla
			   );

			   return $this->render('AdminBundle:Charlas:editar.html.twig',
					array(
						"errores"      => $errores,
						"valores"      => $valores,
						"oStands"      => $oStands,
					)
				);
			}

			$id_charla = $request->query->get('id_charla');
			$oCharlas  = $em->getRepository('AdminBundle:Charlas')->find($id_charla);

			$errores = array(
			   "titulo"                     => "",	
			   "expositor"                  => "",
			   "lugar"                      => "",
			   "fecha"                      => "",
			   "fecha_termino"              => "",
			   "codigoYoutube"              => "",
			);

			$valores = array(
			   "titulo"                     => $oCharlas->getTitulo(),	
			   "expositor"                  => $oCharlas->getExpositor(),
			   "lugar"                      => $oCharlas->getLugar(),
			   "fecha"                      => $oCharlas->getFecha()->format('d-m-Y H:i'),
			   "fecha_termino"             => $oCharlas->getFechaTermino() ? $oCharlas->getFechaTermino()->format('d-m-Y H:i'):"",
			   "codigoYoutube"              => $oCharlas->getCodigoYoutube(),
	           "id_stand"                   => $oCharlas->getIdStand() ? $oCharlas->getIdStand()->getId():"",
	           "id_charla"                  => $id_charla
			);

			return $this->render('AdminBundle:Charlas:editar.html.twig',
				array(
					"errores"      => $errores,
					"valores"      => $valores,
					"oStands"      => $oStands,
				)
			);
	    }

   		/**
		 * @Route("/admin/charlas/eliminar", options={"expose"=true}, name="admin_charlas_eliminar")
		*/
		public function eliminarAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
			
			$id_charla = $request->request->get('id_charla');
			$oCharlas  = $em->getRepository('AdminBundle:Charlas')->find($id_charla);
			
			$em->remove($oCharlas);
			$em->flush();

			$arrRespuesta = array("estado" => "success", "mensaje" => "La charla se ha eliminado correctamente.");
			return new JsonResponse($arrRespuesta);
		}

		/**
		 * @Route("/admin/charlas/bloquear", options={"expose"=true}, name="admin_charlas_bloquear")
		*/
		public function bloquearAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
			
			$id_charla = $request->request->get('id_charla');
			$oCharlas  = $em->getRepository('AdminBundle:Charlas')->find($id_charla);

			$oCharlas->setIdEstado($em->getReference('AdminBundle:Estados', 2));
			$oCharlas->setUpdatedAt($oDateHoy);
			
			$em->persist($oCharlas);
			$em->flush();

			$arrRespuesta = array("estado" => "success", "mensaje" => "La charla se ha bloquear correctamente.");
			return new JsonResponse($arrRespuesta);
		}

		/**
		 * @Route("/admin/charlas/activar", options={"expose"=true}, name="admin_charlas_activar")
		*/
		public function activarAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
			
			$id_charla   = $request->request->get('id_charla');
			$oCharlas    = $em->getRepository('AdminBundle:Charlas')->find($id_charla);

			$oCharlas->setIdEstado($em->getReference('AdminBundle:Estados', 1));
			$oCharlas->setUpdatedAt($oDateHoy);
			
			$em->persist($oCharlas);
			$em->flush();

			$arrRespuesta = array("estado" => "success", "mensaje" => "La charla se ha activado correctamente.");
			return new JsonResponse($arrRespuesta);
		}

		/**
		 * @Route("/admin/charlas/ver", options={"expose"=true}, name="admin_charlas_ver")
		*/
		public function verAction(Request $request){

		  $em = $this->getDoctrine()->getManager();

			// variable para obtenet el host del servidor con el objetivo de funcione el chat de youtube.
	  	   $domain = $_SERVER["HTTP_HOST"];

	  	   $id_charla = $request->request->get('id_charla');
	  	   $oCharlas  = $em->getRepository('AdminBundle:Charlas')->find($id_charla);

	  	   $codigo_youtube = $oCharlas->getCodigoYoutube();

	  	   return $this->render('AdminBundle:Charlas:ver.html.twig',
				array(
					"domain"              => $domain,
					"codigo_youtube"      => $codigo_youtube,
				)
			);
		}
}
