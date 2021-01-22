<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FeriaUC\AdminBundle\Entity\Chats;
use FeriaUC\AdminBundle\Entity\PermisosChatsAdministradores;

class ChatsController extends Controller
{
	/**
	 * @Route("/admin/chats", options={"expose"=true}, name="admin_chats")
	*/
	public function indexAction(){

		$aChats = array();

		$em = $this->getDoctrine()->getManager();

		if($this->getUser()->getRoles()[0] == 'ROLE_FERIA_UC_EMBAJADOR'){
			$aChats = $em->getRepository('AdminBundle:Chats')->obtenerChatsPorEmbajador($this->getUser()->getId());
		} else{
			$aChats = $em->getRepository('AdminBundle:Chats')->obtenerChats();
		}

		return $this->render('AdminBundle:Chats:index.html.twig', 
			array(
				'aChats' => $aChats
			)
		);
	}

	/**
	 * @Route("/admin/chats/agregar", options={"expose"=true}, name="admin_chats_agregar")
	*/
	public function agregarAction(Request $request){
		$em = $this->getDoctrine()->getManager();

		$arrDias = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");

		$errores = array(
			"nombre"             => "",
			"diasFuncionamiento" => "",
			"horaApertura"       => "",
			"horaCierre"         => "",
			"id_stand"           => "",
		);

		$valores = array(
			"nombre"             => "",
			"diasFuncionamiento" => "",
			"horaApertura"       => "09:00",
			"horaCierre"         => "19:00",
			"id_stand"           => ""
		);

		$oStands = $em->getRepository('AdminBundle:Stands')->findAll();

		if ($request->getMethod() === 'POST') {

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$nombre             = $request->request->get('nombre');
			$diasFuncionamiento = $request->request->get('diasFuncionamiento');

			$horaApertura  = $request->request->get('horaApertura');
			$oHoraApertura = new \DateTime(date('H:i', strtotime($horaApertura)));

			$horaCierre  = $request->request->get('horaCierre');
			$oHoraCierre = new \DateTime(date('H:i', strtotime($horaCierre)));
			$id_stand     = $request->request->get('id_stand');


			if($nombre && $diasFuncionamiento && $horaApertura && $horaCierre && $id_stand){
				$oStand = $em->getRepository('AdminBundle:Stands')->find($id_stand);

				$oChats = new Chats();

				$oChats->setNombre($nombre);
				$oChats->setDiasFuncionamiento(implode(',', $diasFuncionamiento));
				$oChats->setHoraApertura($oHoraApertura);
				$oChats->setHoraCierre($oHoraCierre);
				$oChats->setIdEstado($em->getReference('AdminBundle:Estados', 1));
				$oChats->setIdPunto($em->getReference('AdminBundle:Puntos', $oStand->getIdPunto()->getId()));
		    	$oChats->setIdStand($em->getReference('AdminBundle:Stands', $id_stand));
				$oChats->setCreatedAt($oDateHoy);

				try {
					$em->persist($oChats);
					$em->flush();

					$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
					return new JsonResponse($arrRespuesta);

				} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
					$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
					return new JsonResponse($arrRespuesta);
				}
			}

			$errores = array(
				"nombre"                => $nombre ? "":"El nombre es obligatorio",
				"diasFuncionamiento"    => $diasFuncionamiento ? "":"Debe seleccionar menos un día",
				"horaApertura"          => $horaApertura ? "":"La hora de apertura es obligatoria",
				"horaCierre"            => $horaCierre ? "":"La hora de cierre es obligatoria",
				"id_stand"              => $id_stand ? "": "Debe seleccionar un stand"
			);

			$valores = array(
				"nombre"                => $nombre,
				"diasFuncionamiento"    => $diasFuncionamiento,
				"horaApertura"          => $horaApertura,
				"horaCierre"            => $horaCierre,
				"id_stand"              => $id_stand
			);

		}
		
		return $this->render('AdminBundle:Chats:agregar.html.twig', 
			array(
				'arrDias' => $arrDias,
				'errores' => $errores,
				'valores' => $valores,
				'oStands' => $oStands
			)
		); 
	}

	/**
	 * @Route("/admin/chats/editar", options={"expose"=true}, name="admin_chats_editar")
	*/

	public function editarAction(Request $request){

		$arrDias = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");

		$em = $this->getDoctrine()->getManager();

		$oStands = $em->getRepository('AdminBundle:Stands')->findAll();

		if ($request->getMethod() === 'POST') {

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$id_chat        = $request->request->get('id_chat');
			$oChats         = $em->getRepository('AdminBundle:Chats')->find($id_chat);

			$nombre             = $request->request->get('nombre');
			$diasFuncionamiento = $request->request->get('diasFuncionamiento');

			$horaApertura  = $request->request->get('horaApertura');
			$oHoraApertura = new \DateTime(date('H:i', strtotime($horaApertura)));

			$horaCierre  = $request->request->get('horaCierre');
			$oHoraCierre = new \DateTime(date('H:i', strtotime($horaCierre)));

			$id_stand     = $request->request->get('id_stand');

			if($nombre && $diasFuncionamiento && $horaApertura && $horaCierre && $id_stand){

                $oStand = $em->getRepository('AdminBundle:Stands')->find($id_stand);

				$oChats->setNombre($nombre);
				$oChats->setDiasFuncionamiento(implode(',', $diasFuncionamiento));
				$oChats->setHoraApertura($oHoraApertura);
				$oChats->setHoraCierre($oHoraCierre);
				$oChats->setIdPunto($em->getReference('AdminBundle:Puntos', $oStand->getIdPunto()->getId()));
				$oChats->setIdStand($em->getReference('AdminBundle:Stands', $id_stand));
				$oChats->setUpdatedAt($oDateHoy);

				try {
					$em->persist($oChats);
					$em->flush();

					$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
					return new JsonResponse($arrRespuesta);

				} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
					$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
					return new JsonResponse($arrRespuesta);
				}
			}

			$errores = array(
				"nombre"                => $nombre ? "":"El nombre es obligatorio",
				"diasFuncionamiento"    => $diasFuncionamiento ? "":"Debe seleccionar menos un día",
				"horaApertura"          => $horaApertura ? "":"La hora de apertura es obligatoria",
				"horaCierre"            => $horaCierre ? "":"La hora de cierre es obligatoria",
				"id_stand"              => $id_stand ? "": "Debe seleccionar un stand"
			);

			$valores = array(
				"nombre"                => $nombre,
				"diasFuncionamiento"    => $diasFuncionamiento,
				"horaApertura"          => $horaApertura,
				"horaCierre"            => $horaCierre,
				"id_stand"              => $id_stand,
				"id_chat"               => $id_chat
			);

			return $this->render('AdminBundle:Chats:editar.html.twig', 
				array(
					'arrDias' => $arrDias,
					'errores' => $errores,
					'valores' => $valores,
					'oStands' => $oStands
				)
			); 
		}

		$id_chat        = $request->query->get('id_chat');
		$oChats         = $em->getRepository('AdminBundle:Chats')->find($id_chat);

		$errores = array(
			"nombre"             => "",
			"diasFuncionamiento" => "",
			"horaApertura"       => "",
			"horaCierre"         => "",
			"id_stand"           => "",
		);

		$valores = array(
			"nombre"                => $oChats->getNombre(),
			"diasFuncionamiento"    => explode(',', $oChats->getDiasFuncionamiento()),
			"horaApertura"          => $oChats->getHoraApertura()->format('H:i'),
			"horaCierre"            => $oChats->getHoraCierre()->format('H:i'),
			"id_stand"              => $oChats->getIdStand() ? $oChats->getIdStand()->getId(): "",
			"id_chat"               => $id_chat
		);

		return $this->render('AdminBundle:Chats:editar.html.twig', 
			array(
				'arrDias' => $arrDias,
				'errores' => $errores,
				'valores' => $valores,
				'oStands' => $oStands
			)
		); 
	}

	  /**
	 * @Route("/admin/chats/eliminar", options={"expose"=true}, name="admin_chats_eliminar")
	 */
	  public function eliminarAction(Request $request){

	  	$em = $this->getDoctrine()->getManager();

	  	date_default_timezone_set("America/Santiago");

	  	$dateHoy  = date("Y-m-d H:i:s");
	  	$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

	  	$id_chat        = $request->request->get('id_chat');
	  	$oChats         = $em->getRepository('AdminBundle:Chats')->find($id_chat);

	  	$oChats->setIdEstado($em->getReference('AdminBundle:Estados', 3));
	  	$oChats->setUpdatedAt($oDateHoy);

	  	$em->persist($oChats);
	  	$em->flush();

	  	$arrRespuesta = array("estado" => "success", "mensaje" => "El chat se ha eliminado correctamente.");
	  	return new JsonResponse($arrRespuesta);
	  }

	/**
	 * @Route("/admin/chats/bloquear", options={"expose"=true}, name="admin_chats_bloquear")
	*/
	public function bloquearAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		date_default_timezone_set("America/Santiago");

		$dateHoy  = date("Y-m-d H:i:s");
		$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
		
		$id_chat        = $request->request->get('id_chat');
		$oChats         = $em->getRepository('AdminBundle:Chats')->find($id_chat);

		$oChats->setIdEstado($em->getReference('AdminBundle:Estados', 2));
		$oChats->setUpdatedAt($oDateHoy);
		
		$em->persist($oChats);
		$em->flush();

		$arrRespuesta = array("estado" => "success", "mensaje" => "El chat se ha bloquear correctamente.");
		return new JsonResponse($arrRespuesta);
	}

	/**
	 * @Route("/admin/chats/activar", options={"expose"=true}, name="admin_chats_activar")
	*/
	public function activarAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		date_default_timezone_set("America/Santiago");

		$dateHoy  = date("Y-m-d H:i:s");
		$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
		
		$id_chat        = $request->request->get('id_chat');
		$oChats         = $em->getRepository('AdminBundle:Chats')->find($id_chat);

		$oChats->setIdEstado($em->getReference('AdminBundle:Estados', 1));
		$oChats->setUpdatedAt($oDateHoy);
		
		$em->persist($oChats);
		$em->flush();

		$arrRespuesta = array("estado" => "success", "mensaje" => "El chat se ha activado correctamente.");
		return new JsonResponse($arrRespuesta);
	}

	 /**
	 * @Route("/admin/chats/asignar/permisos", options={"expose"=true}, name="admin_chats_asignar_permisos")
	*/
	 public function asignarPermisosAction(Request $request){

	 	$aIdAdministradores = array();
	 	$aAdministradores   = array();
	 	$aIdAdministradoresPermisos = array();
	 	$errores            = array('administradores' => '');

	 	$em = $this->getDoctrine()->getManager();

	 	$oAdministradores = $em->getRepository('AdminBundle:Administradores')->findBy(array('idEstadoUsuario' => [1,2]));

	 	if ($request->getMethod() === 'POST') {

	 		$dateHoy  = date("Y-m-d H:i:s");
	 		$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

	 		$id_chat          = $request->request->get('id_chat');
	 		$administradores  = $request->request->get('administradores');

	 		if($administradores){

	 			foreach ($administradores as $id_administrador) {

	 				$oPermisosChatsAdministradores = new PermisosChatsAdministradores();

	 				$oPermisosChatsAdministradores->setIdAdministrador($em->getReference('AdminBundle:Administradores', $id_administrador));
	 				$oPermisosChatsAdministradores->setIdChat($em->getReference('AdminBundle:Chats', $id_chat));
	 				$oPermisosChatsAdministradores->setCreatedAt($oDateHoy);

	 				$em->persist($oPermisosChatsAdministradores);
	 				$em->flush();
	 			}
	 			$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
	 			return new JsonResponse($arrRespuesta);
	 		}

	 		$errores   = array('administradores' => 'Debe selecionar por lo menos a uno');

	 		foreach ($oAdministradores as $administrador) {
	 			foreach ($administrador->getRoles() as $perfil) {
	 				if($perfil == 'ROLE_FERIA_UC_ADMIN' || $perfil == 'ROLE_FERIA_UC_EMBAJADOR'){
	 					$auxIdAdministrador = $administrador->getId();
	 					$aIdAdministradores[] = $auxIdAdministrador;
	 					$aAdministradores[$auxIdAdministrador]['nombre']   = $administrador->getNombre();
	 					$aAdministradores[$auxIdAdministrador]['username'] = $administrador->getUsername();
	 				}
	 			}
	 		}

	 		$aObtenerAdministradoresConPermisos = $em->getRepository('AdminBundle:PermisosChatsAdministradores')->obtenerAdministradoresConPermisos($aIdAdministradores, $id_chat);

	 		foreach ($aObtenerAdministradoresConPermisos as $administradorPermiso) {
	 			$aIdAdministradoresPermisos[] = $administradorPermiso['idAdministrador'];
	 		}

	 		foreach ($aAdministradores as $key => $administrador) {	
	 			if (in_array($key, $aIdAdministradoresPermisos)) {
	 				unset($aAdministradores[$key]);
	 			}	
	 		}

	 		return $this->render('AdminBundle:Chats:asignar.html.twig', 
	 			array(
				"aObtenerAdministradoresConPermisos" => $aObtenerAdministradoresConPermisos,//con permisos
				"aAdministradores"                   => $aAdministradores,//todos los id de los administradores
				"errores"                            => $errores,
				"id_chat"                            => $id_chat 
			)
	 		); 
	 	}

	 	$id_chat  = $request->query->get('id_chat');

	 	foreach ($oAdministradores as $administrador) {
	 		foreach ($administrador->getRoles() as $perfil) {
	 			if($perfil == 'ROLE_FERIA_UC_ADMIN' || $perfil == 'ROLE_FERIA_UC_EMBAJADOR'){
	 				$auxIdAdministrador = $administrador->getId();
	 				$aIdAdministradores[] = $auxIdAdministrador;
	 				$aAdministradores[$auxIdAdministrador]['nombre']   = $administrador->getNombre();
	 				$aAdministradores[$auxIdAdministrador]['username'] = $administrador->getUsername();
	 			}
	 		}
	 	}

	 	$aObtenerAdministradoresConPermisos = $em->getRepository('AdminBundle:PermisosChatsAdministradores')->obtenerAdministradoresConPermisos($aIdAdministradores, $id_chat);

	 	foreach ($aObtenerAdministradoresConPermisos as $administradorPermiso) {
	 		$aIdAdministradoresPermisos[] = $administradorPermiso['idAdministrador'];
	 	}

	 	foreach ($aAdministradores as $key => $administrador) {	
	 		if (in_array($key, $aIdAdministradoresPermisos)) {
	 			unset($aAdministradores[$key]);
	 		}	
	 	}

	 	return $this->render('AdminBundle:Chats:asignar.html.twig', 
	 		array(
			"aObtenerAdministradoresConPermisos" => $aObtenerAdministradoresConPermisos,//con permisos
			"aAdministradores"                   => $aAdministradores,//todos los id de los administradores
			"errores"                            => $errores,
			"id_chat"                            => $id_chat 
		)
	 	);      
	 }

	/**
	 * @Route("/admin/chats/eliminar/permisos", options={"expose"=true}, name="admin_chats_eliminar_permisos")
	*/
	public function eliminarPermisosAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		$id_permiso_chat_administrador  = $request->request->get('id_permiso_chat_administrador');
		$oPermisosChatsAdministradores   = $em->getRepository('AdminBundle:PermisosChatsAdministradores')->find($id_permiso_chat_administrador);

		$em->remove($oPermisosChatsAdministradores);
		$em->flush();

		$arrRespuesta = array("estado" => "OK", "mensaje" => "Fue eliminada la asignacion");
		return new JsonResponse($arrRespuesta);
	}

	/**
	 * @Route("/admin/chats/obtener/stands", options={"expose"=true}, name="admin_chats_obtener_stands")
	*/ 
	public function obtenerStandsAction(Request $request){

		$arrStands  = array();
		$arrIdstand = array(); 

		$em = $this->getDoctrine()->getManager();

		$id_punto = $request->request->get('id_punto');
		$oPuntos  = $em->getRepository('AdminBundle:Puntos')->find($id_punto);

		$id_stand = $request->request->get('id_stand');

		if($oPuntos->getIdTipoPunto()->getId() == 2 ){

			if($id_stand){
				$oStands = $em->getRepository('AdminBundle:Stands')->find($id_stand);
				$arrIdstand []  = array('id' => $oStands->getId(), 'nombre' => $oStands->getNombre());
			}

			$oStands = $em->getRepository('AdminBundle:Stands')->findBy(array('idPunto' => $id_punto));

			foreach ($oStands as  $id_stand) {
				$oChats = $em->getRepository('AdminBundle:Chats')->findOneBy(array('idStand' => $id_stand->getId()));
				if(!$oChats){
					$arrStands []  = array('id' => $id_stand->getId(), 'nombre' => $id_stand->getNombre());
				}
			}

			if(count($arrIdstand) > 0){
				$arrStands =  array_merge($arrStands, $arrIdstand);
			}
		}
		return new JsonResponse($arrStands);
	}

   /**
   * @Route("/admin/chats/ver", options={"expose"=true}, name="admin_chats_ver")
   */
   public function verAction(Request $request){

   	$em = $this->getDoctrine()->getManager();

   	$id_chat   = $request->request->get('id_chat');
   	$oChats    = $em->getRepository('AdminBundle:Chats')->find($id_chat);

   	$oPermisosChatsAdministradores = $em->getRepository('AdminBundle:PermisosChatsAdministradores')->findBy(array('idChat' => $id_chat));

   	return $this->render('AdminBundle:Chats:ver.html.twig', 
   		array(
   			'oChats'                         => $oChats,
   			'oPermisosChatsAdministradores'  => $oPermisosChatsAdministradores
   		)
   		
   	);
   }
}
