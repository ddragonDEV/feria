<?php

namespace FeriaUC\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use FeriaUC\AdminBundle\Entity\ChatsMensajes;


class ChatsMensajesController extends Controller
{
	/**
	 * @Route("/chat", options={"expose"=true}, name="chat")
	*/
	public function indexAction(){

		$session = new Session();

		if($session->has('usuario')) {

			$em = $this->getDoctrine()->getManager();

			$oChats = $em->getRepository('AdminBundle:Chats')->obtenerChats();

			return $this->render('AppBundle:ChatsMensajes:index.html.twig', 
				array(
					'oChats'  => $oChats 
				)
			);
		}
		return $this->redirectToRoute('login');
	}

	 /**
	 * @Route("/chat/responder", options={"expose"=true}, name="chat_responder")
	*/
	 public function responderAction(Request $request){

	 	$arrChatsMensajes = array();

	 	$session = new Session();

	 	if($session->has('usuario')) {

	 		$em = $this->getDoctrine()->getManager();

	 		if ($request->getMethod() === 'POST') {

	 			date_default_timezone_set("America/Santiago");

	 			$dateHoy  = date("Y-m-d H:i:s");
	 			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

	 			$texto         = $request->request->get('texto');              
	 			$id_chat       = $request->request->get('id_chat');

	 			if($texto){

	 				$oChatsMensajes =  $em->getRepository('AdminBundle:ChatsMensajes')->findOneBy(
	 					array(
	 						'idUsuario'   => $session->get('usuario')->getId(),
	 						'idChat'      => $id_chat
	 					)
	 				);

	 				if($oChatsMensajes){
	 					$arrChatsMensajes  = json_decode($oChatsMensajes->getJsonMensajes());
	 				} else{  
	 					$oChatsMensajes = new ChatsMensajes();

	 					$oChatsMensajes->setIdEstado($em->getReference('AdminBundle:Estados', 1));
	 				}

	 				$arrChatsMensajes [] = array(
	 					'id'       => count($arrChatsMensajes) + 1,
	 					'idNovato' => $session->get('usuario')->getId(),
	 					'novato'   => $session->get('usuario')->getNombre().' '.$session->get('usuario')->getApellidos(),
	 					'texto'    => $texto, 
	 					'fecha'    => $oDateHoy, 
	 					'estado'   => 'enviado'
	 				);

	 				$contadorSinResponder = $oChatsMensajes->getContadorSinResponder() + 1;

	 				$oChatsMensajes->setIdChat($em->getReference('AdminBundle:Chats', $id_chat));
	 				$oChatsMensajes->setIdUsuario($em->getReference('AdminBundle:Usuarios', $session->get('usuario')->getId()));
	 				$oChatsMensajes->setJsonMensajes(json_encode($arrChatsMensajes));
	 				$oChatsMensajes->setContadorSinResponder($contadorSinResponder);
	 				$oChatsMensajes->setcreatedAt($oDateHoy);

	 				try {
	 					$em->persist($oChatsMensajes);
	 					$em->flush();

	 					$html = array("texto" => $texto, "fecha" => $oDateHoy->format('H:i A').' | ' .$oDateHoy->format('M d'));

	 					$arrRespuesta = array("estado" => "OK", "html" => $html);
	 					return new JsonResponse($arrRespuesta);

	 				} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

	 					$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
	 					return new JsonResponse($arrRespuesta);
	 				}
	 			}
	 		}

	 		$id_chat       = $request->query->get('id_chat');

	 		$oChatsMensajes =  $em->getRepository('AdminBundle:ChatsMensajes')->findOneBy(
	 			array(
	 				'idUsuario' => $session->get('usuario')->getId(),
	 				'idChat'    => $id_chat
	 			)
	 		);

	 		if($oChatsMensajes){
	 			if( $oChatsMensajes->getIdEstado()->getId() == 2 ){
	 				return $this->render('AppBundle:ChatsMensajes:bloqueado.html.twig', array('flat' => false));
	 			}
	 			$arrChatsMensajes = json_decode($oChatsMensajes->getJsonMensajes());
	 		}

	 		return $this->render('AppBundle:ChatsMensajes:responder.html.twig', 
	 			array(
	 				'arrChatsMensajes' => $arrChatsMensajes,
	 				'id_chat'           => $id_chat
	 			)
	 		);
	 	}
	 	return $this->redirectToRoute('login');
	 }

	/**
	 * @Route("/load-chat",  options={"expose"=true},  name="load_chat")
	*/
	public function loadChatAction(Request $request){

		$session = new Session();

		$id_chat                 = $request->request->get('id_chat');
		$id_ultimo_json_mensaje  = $request->request->get('id_ultimo_json_mensaje');

		if($session->has('usuario')) {

			$arrChatsMensajes = array();

			$em = $this->getDoctrine()->getManager();

			$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->findOneBy(
				array(
					'idUsuario'   => $session->get('usuario')->getId(),
					'idChat'      => $id_chat
				)
			);

			if($oChatsMensajes){
				if( $oChatsMensajes->getIdEstado()->getId() == 2 ){
					return $this->render('AppBundle:ChatsMensajes:bloqueado.html.twig', array('flat' => true));
				}
				$arrChatsMensajes = json_decode($oChatsMensajes->getJsonMensajes());
				$arrChatsMensajes = end($arrChatsMensajes);

				if($arrChatsMensajes->estado == 'respondido' && $arrChatsMensajes->id != $id_ultimo_json_mensaje){

					$texto = $arrChatsMensajes->texto;

					$fecha = $arrChatsMensajes->fecha->date;
				    $oFecha = new \DateTime(date('Y-m-d H:i', strtotime($fecha)));

				    $html = array("texto" => $texto, "fecha" => $oFecha->format('H:i A').' | ' .$oFecha->format('M d'));

	 				$arrRespuesta = array("estado" => "OK", "html" => $html, 'id_ultimo_json_mensaje' => $arrChatsMensajes->id);
	 				return new JsonResponse($arrRespuesta);
				}
			}

			$arrRespuesta = array('id_ultimo_json_mensaje' => $id_ultimo_json_mensaje);
	 		return new JsonResponse($arrRespuesta);
		}
		return $this->redirectToRoute('login');
	}
}
