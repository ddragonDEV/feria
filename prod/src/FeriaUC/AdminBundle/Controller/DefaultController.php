<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DefaultController extends Controller
{

	/**
     * @Route("/admin", options={"expose"=true}, name="admin")
    */

	 public function inicioAction(){

	 	return $this->render('AdminBundle:Default:index.html.twig');
	 }

    /**
    * @Route("/admin/login", options={"expose"=true}, name="admin_login")
    */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils){
        $errors       = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('AdminBundle:Login:index.html.twig',
			array(
				'errors'   => $errors,
				'username' => $lastUsername
			)
		);
    }

    /**
    * @Route("/admin/perfil", options={"expose"=true}, name="admin_perfil")
    */
     public function PerfilAction(Request $request){

     	$em = $this->getDoctrine()->getManager();

     	if ($request->getMethod() === 'POST') {

     		$id_administrador = $this->getUser()->getId();

     		$oAdmintradores   = $em->getRepository('AdminBundle:Administradores')->find($id_administrador);

     		$nombre     = $request->request->get('nombre');
     		$apellidos  = $request->request->get('apellidos');
     	
     		if($nombre && $apellidos){

     			$oAdmintradores->setNombre($nombre);
     			$oAdmintradores->setApellidos($apellidos);
     		
     			try {
					$em->persist($oAdmintradores);
					$em->flush();

					$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
					return new JsonResponse($arrRespuesta);
				} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

					$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
					return new JsonResponse($arrRespuesta);
				}
     		}
     		$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Debe escribir en todos los campos obligatorios (*)");
	        return new JsonResponse($arrRespuesta);
     	}

		return $this->render('AdminBundle:Default:perfil.html.twig');
    }

    /**
	 * @Route("/admin/logout", name="admin_logout")
	*/
	public function logoutAction(){}
}
