<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FeriaUC\AdminBundle\Entity\ChatsMensajes;


class ChatsMensajesController extends Controller
{
	/**
	 * @Route("/admin/chats/mensajes/{id_chat}", options={"expose"=true}, name="admin_chats_mensajes")
	 */
	public function indexAction(Request $request, $id_chat=null){
		$em = $this->getDoctrine()->getManager();
		// aqui voy a listar todos los chats que tiene habilitado, se supoque que estando aca deberia tener los permisos 

		$embajadorId = $this->getUser()->getId();

		// si viene el id del chat lo listo de los primeros
		$flag   = false;
		$aChats = array();
		if($id_chat){
			// si tiene perfil de embajador corre esto: hay que preguntar si tiene permiso o no, porque que pasa si cambia el id en la url ??			
			$flag_administrador = false;
			foreach ($this->getUser()->getRoles() as $role) {
				if($role == 'ROLE_FERIA_UC_SUPER_ADMIN' || $role == 'ROLE_FERIA_UC_ADMIN'){
					$flag_administrador = true;
				}	
			}
			
			if($flag_administrador){
				// recuerda que falta guardar el mensaje del embajador para que cambie el contador sin cotestar 
				return $this->render('AdminBundle:ChatsMensajes:index.html.twig',
					array(
						"id_chat"      => $id_chat,
						"id_embajador" => $embajadorId
					)
				);
			}else{
				$oPermisosChatsAdministradores = $em->getRepository('AdminBundle:PermisosChatsAdministradores')->findOneBy(array('idChat' => $id_chat, 'idAdministrador' => $embajadorId));
				if($oPermisosChatsAdministradores){
					return $this->render('AdminBundle:ChatsMensajes:index.html.twig',
						array(
							"id_chat"      => $id_chat,
							"id_embajador" => $embajadorId
						)
					);
				}
				return $this->redirectToRoute('admin_chats');
			}

			// recuerda que falta guardar el mensaje del embajador para que cambie el contador sin cotestar 
			return $this->render('AdminBundle:ChatsMensajes:index.html.twig',
				array(
					"id_chat"      => $id_chat,
					"id_embajador" => $embajadorId
				)
			);
		}
		return $this->redirectToRoute('admin_chats');
	}

	/**
	 * @Route("/admin/chats/guardar-mensaje-embajador", options={"expose"=true}, name="admin_guardar_mensaje_embajador")
	*/
	public function guardarMensajeAction(Request $request){
		$em = $this->getDoctrine()->getManager();
		
		$idChat      = $request->request->get('idChat');
		$idEmbajador = $request->request->get('idEmbajador');
		$idUsuario   = $request->request->get('idUsuario');
		$mensaje     = $request->request->get('mensaje');
		$timestamp   = $request->request->get('timestamp');

		if($idChat && $idUsuario && $idEmbajador){
			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->findOneBy(array('idChat' => $idChat, 'idUsuario' => $idUsuario));
			if($oChatsMensajes){//deberia siempre existir
				$arrChatsMensajes  = json_decode($oChatsMensajes->getJsonMensajes());

				$arrChatsMensajes [] = array(
					'id'           => count($arrChatsMensajes) + 1,
					'id_embajador' => $idEmbajador,
					'texto'        => $mensaje, 
					'timestamp'    => strtotime("now"),
					'tipo'         => 'embajador'
			 	);

				$oChatsMensajes->setJsonMensajes(json_encode($arrChatsMensajes));
				$oChatsMensajes->setUpdatedAt($oDateHoy);

				$em->persist($oChatsMensajes);
	 			$em->flush();

				$arrRespuesta = array("estado" => "OK", "mensaje" => "Se han guardado los datos correctamente");
				return new JsonResponse($arrRespuesta);
			}

			$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
			return new JsonResponse($arrRespuesta);
		}

		$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
		return new JsonResponse($arrRespuesta);
	}

	/**
	 * @Route("/admin/chats/obtener-chats-escuchados", options={"expose"=true}, name="admin_obtener_chats_escuchados")
	*/
	public function obtenerChatEscuchadosAction(Request $request){		
		$em = $this->getDoctrine()->getManager();

		$embajadorId = $this->getUser()->getId();
		$id_chat     = $request->request->get('idChat');

		$flag_administrador = false;
		foreach ($this->getUser()->getRoles() as $role) {
			if($role == 'ROLE_FERIA_UC_SUPER_ADMIN'){
				$flag_administrador = true;
			}	
		}

		$aChats = array();
		if(!$flag_administrador){
			$oPermisosChatsAdministradores = $em->getRepository('AdminBundle:PermisosChatsAdministradores')->findOneBy(array('idChat' => $id_chat, 'idAdministrador' => $embajadorId));

			if($oPermisosChatsAdministradores){
				$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->findBy(array('idChat' => $id_chat));
				if ($oChatsMensajes) {
					foreach ($oChatsMensajes as $chatsMensajes) {
						$arrChatsMensajes = json_decode($chatsMensajes->getJsonMensajes());
						$count = 0;
						foreach($arrChatsMensajes as $mensajes) {
							if ($mensajes->tipo == "novato") {
								$count++;
							}
							if ($mensajes->tipo == "embajador") {
								$count=0;
							}
						}
						$aChats[$chatsMensajes->getIdChat()->getId()] = array(
							"idChat"        => $chatsMensajes->getIdChat()->getId(),
							"nombre"        => $chatsMensajes->getIdUsuario()->getNombre()." ".$chatsMensajes->getIdUsuario()->getApellidos(),
							"avatar"        => $chatsMensajes->getIdUsuario()->getImagenPerfil(),
							"idUsuario"     => $chatsMensajes->getIdUsuario()->getId(),
							"sin_responder" => $count
						);
					}
				}
			}
		}else{
			$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->findBy(array('idChat' => $id_chat));

			if ($oChatsMensajes) {
				foreach ($oChatsMensajes as $chatsMensajes) {
					$arrChatsMensajes = json_decode($chatsMensajes->getJsonMensajes());
					$count = 0;
					foreach($arrChatsMensajes as $mensajes) {
						if ($mensajes->tipo == "novato") {
							$count++;
						}
						if ($mensajes->tipo == "embajador") {
							$count=0;
						}
					}

					$aChats[$chatsMensajes->getIdUsuario()->getId()] = array(
						"idChat"        => $chatsMensajes->getIdChat()->getId(),
						"nombre"        => $chatsMensajes->getIdUsuario()->getNombre()." ".$chatsMensajes->getIdUsuario()->getApellidos(),
						"avatar"        => $chatsMensajes->getIdUsuario()->getImagenPerfil(),
						"idUsuario"     => $chatsMensajes->getIdUsuario()->getId(),
						"sin_responder" => $count
					);
				}
			}
		}
 		
 		// echo '<pre>';var_dump($aChats);exit;
		return $this->render('AdminBundle:ChatsMensajes:mostrar-chats-escuchados.html.twig',
			array(
				"aChats" => $aChats
			)
		);
	}

	/**
	 * @Route("/admin/chats/novato/load",  options={"expose"=true},  name="admin_chats_novato_load")
	*/
	public function loadAction(Request $request){

		$arrChatsMensajes = array();

		$id_mensaje               = $request->request->get('id_mensaje');
		$id_ultimo_json_mensaje   = $request->request->get('id_ultimo_json_mensaje');

		$em = $this->getDoctrine()->getManager();

		$oChatsMensajes    = $em->getRepository('AdminBundle:ChatsMensajes')->find($id_mensaje);

		$arrChatsMensajes = json_decode($oChatsMensajes->getJsonMensajes());
		$arrChatsMensajes = end($arrChatsMensajes);

		if($arrChatsMensajes->estado == 'enviado' && $arrChatsMensajes->id != $id_ultimo_json_mensaje){

			$texto = $arrChatsMensajes->texto;

			$fecha = $arrChatsMensajes->fecha->date;
			$oFecha = new \DateTime(date('Y-m-d H:i', strtotime($fecha)));

			$html = array("texto" => $texto, "fecha" => $oFecha->format('H:i A').' | ' .$oFecha->format('M d'));

			$arrRespuesta = array("estado" => "OK", "html" => $html, 'id_ultimo_json_mensaje' => $arrChatsMensajes->id);
			return new JsonResponse($arrRespuesta);
		}
		$arrRespuesta = array('id_ultimo_json_mensaje' => $id_ultimo_json_mensaje);
		return new JsonResponse($arrRespuesta);
	}

	 /**
	 * @Route("/admin/chats/novato/bloquear", options={"expose"=true}, name="admin_chats_novato_bloquear")
	 */
 	public function bloquearAction(Request $request){

	 	$em = $this->getDoctrine()->getManager();

	 	$id_mensaje = $request->request->get('id_mensaje');

	 	$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->find($id_mensaje);

	 	$oChatsMensajes->setIdEstado($em->getReference('AdminBundle:Estados', 2));

	 	$em->persist($oChatsMensajes);
	 	$em->flush();

	 	$arrRespuesta = array("estado" => "success", "mensaje" => "El chat se ha bloquear correctamente para el novato.");
	 	return new JsonResponse($arrRespuesta);
 	}

	/**
	 * @Route("/admin/chats/novato/activar", options={"expose"=true}, name="admin_chats_novato_activar")
	 */
	public function activarAction(Request $request){

		$em = $this->getDoctrine()->getManager();
		
		$id_mensaje = $request->request->get('id_mensaje');

		$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->find($id_mensaje);

		$oChatsMensajes->setIdEstado($em->getReference('AdminBundle:Estados', 1));
		
		$em->persist($oChatsMensajes);
		$em->flush();

		$arrRespuesta = array("estado" => "success", "mensaje" => "El chat se ha activado correctamente para el novato.");
		return new JsonResponse($arrRespuesta);
	}
}
