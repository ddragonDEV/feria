<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FeriaUC\AdminBundle\Entity\Administradores;

class AdministradoresController extends Controller
{
	/**
	 * @Route("/admin/administradores", options={"expose"=true}, name="admin_administradores")
	*/
	public function indexAction(){

		$em = $this->getDoctrine()->getManager();

		$aAdministradores = $em->getRepository('AdminBundle:Administradores')->obtenerAdministradores();

		return $this->render('AdminBundle:Administradores:index.html.twig', 
			array(
				'aAdministradores' => $aAdministradores
			)
		);
	}

	/**
	 * @Route("/admin/administradores/agregar", options={"expose"=true}, name="admin_administradores_agregar")
	*/
	public function agregarAction(Request $request){

		$em = $this->getDoctrine()->getManager();
		
		if ($request->getMethod() === 'POST') {

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$nombre              = $request->request->get('nombre');
			$apellidos           = $request->request->get('apellidos');
			$username            = $request->request->get('username');
			$roles               = $request->request->get('roles');
			$password_first      = $request->request->get('password_first');
			$password_second     = $request->request->get('password_second');
			//$avatar              = $request->request->get('avatar');


			if($nombre && $username && $roles  && $password_first) {

				if (filter_var($username, FILTER_VALIDATE_EMAIL)) {

					if($password_first === $password_second){

						$passwordEncoder = $this->get('security.password_encoder');

						$oAdmintradores = new Administradores();

						$oAdmintradores->setNombre($nombre);
						$oAdmintradores->setApellidos($apellidos);
						$oAdmintradores->setUsername($username);
						$oAdmintradores->setRoles([$roles]);
						$oAdmintradores->setIdEstadoUsuario($em->getReference('AdminBundle:EstadosUsuarios', 1));
					
						$password = $passwordEncoder->encodePassword($oAdmintradores, $password_first);
						$oAdmintradores->setPassword($password);
						$oAdmintradores->setCreatedAt($oDateHoy);
 
						try {

							$em->persist($oAdmintradores);
							$em->flush();

							$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente", "datos" => array("id_embajador" => $oAdmintradores->getId(), "nombre" => $nombre." ".$apellidos, "avatar" => "embajadores.jpg"));
							return new JsonResponse($arrRespuesta);

						} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
							if($e->getErrorCode() == 1062){
								$errores = array(
									"nombre"                 => "",
									"email"                  => "Ya existe el email",
									"roles"                  => "",
									"password"               => "",
									"password_second"        => "",
								);

								$valores = array(
									"nombre"             => $nombre,
									"apellidos"          => $apellidos,
									"email"              => $username,
									"roles"              => $roles,            
								);

								return $this->render('AdminBundle:Administradores:agregar.html.twig',
									array(
										"errores"   => $errores,
										"valores"   => $valores,
									)
								);
							}else{                          
								$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
								return new JsonResponse($arrRespuesta);
							}
						}
					}

                    $errores = array(
                        "nombre"          => "",
                        "email"           => "",
                        "avatar"          => "",
                        "password"        => "Las contraseñas no soy iguales",
                        "password_second" => "Las contraseñas no soy iguales"
                    );
                    
                    $valores = array(
                        "nombre"             => $nombre,
                        "apellidos"          => $apellidos,
                        "email"              => $username,
                        "roles"              => $roles
                    );
                    
                    return $this->render('AdminBundle:Administradores:agregar.html.twig',
                        array(
                            "errores"   => $errores,
                            "valores"   => $valores
                        )
                    );
				}

				$errores = array(
                    "nombre"          => "",
                    "email"           => "Email inválido",
                    "roles"           => "",
                    "avatar"          => "",
                    "password"        => "",
                    "password_second" => ""
			   );

				$valores = array(
					"nombre"             => $nombre,
					"apellidos"          => $apellidos,
					"email"              => $username,
					"roles"              => $roles
				);

				return $this->render('AdminBundle:Administradores:agregar.html.twig',
					array(
						"errores"   => $errores,
						"valores"   => $valores
					)
				);
			}

			$errores = array(
				"nombre"             => ($nombre)?"":"El nombre es obligatorio",
				"email"              => ($username)?"":"El email es obligatorio",
				"roles"              => ($roles)?"":"Debe seleccionar un rol",
				"password"           => ($password_first)?"":"La Contraseña es obligatoria",
				"password_second"    => ($password_second)?"":"La Contraseña es obligatoria",
			);

			$valores = array(
				"nombre"             => $nombre,
				"apellidos"          => $apellidos,
				"email"              => $username,
				"roles"              => $roles              
			);

			return $this->render('AdminBundle:Administradores:agregar.html.twig',
				array(
					"errores"   => $errores,
					"valores"   => $valores
				)
			);
		}

		$errores = array(
			"nombre"             => "",
			"email"              => "",
			"roles"              => "",
			"password"           => "",
			"password_second"    => ""
		);

		$valores = array(
			"nombre"             => "",
			"apellidos"          => "",
			"email"              => "",
			"roles"              => ""
		);

		return $this->render('AdminBundle:Administradores:agregar.html.twig',
			array(
				"errores"   => $errores,
				"valores"   => $valores
			)
		);
	}

	/**
	 * @Route("/admin/administradores/editar", options={"expose"=true}, name="admin_administradores_editar")
	*/
	public function editarAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		if ($request->getMethod() === 'POST') {

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$id_administrador        = $request->request->get('id_administrador');
			$oAdmintradores          = $em->getRepository('AdminBundle:Administradores')->find($id_administrador);

			$nombre              = $request->request->get('nombre');
			$apellidos           = $request->request->get('apellidos');
			$username            = $request->request->get('username');
			$roles               = $request->request->get('roles');
			$password_first      = $request->request->get('password_first');
			$password_second     = $request->request->get('password_second');
			//$avatar              = $request->request->get('avatar');

			if($nombre && $username && $roles){

				if (filter_var($username, FILTER_VALIDATE_EMAIL)) {

					if(empty($password_first)){

						$oAdmintradores->setNombre($nombre);
						$oAdmintradores->setApellidos($apellidos);
						$oAdmintradores->setRoles([$roles]);
						$oAdmintradores->setUpdatedAt($oDateHoy);

						try {

							$em->persist($oAdmintradores);
							$em->flush();

							$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente", "datos" => array("id_embajador" => $oAdmintradores->getId(), "nombre" => $nombre." ".$apellidos, "avatar" => "embajadores.jpg"));
							return new JsonResponse($arrRespuesta);

						} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
							if($e->getErrorCode() == 1062){
								$errores = array(
									"nombre"         => "",
									"email"                  => "Ya existe el email",
									"roles"                  => "",
									"password"               => "",
									"password_second"        => "",
								);

								$valores = array(
									"nombre"             => $nombre,
									"apellidos"          => $apellidos,
									"email"              => $username,
									"roles"              => $roles,
									"id_administrador"   => $id_administrador              
								);

								return $this->render('AdminBundle:Administradores:editar.html.twig',
									array(
										"errores"   => $errores,
										"valores"   => $valores
									)
								);
							}else{                          
								$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
								return new JsonResponse($arrRespuesta);
							}
						}

					} else {

						if($password_first === $password_second){

							$passwordEncoder = $this->get('security.password_encoder');

							$oAdmintradores->setNombre($nombre);
							$oAdmintradores->setApellidos($apellidos);
							$oAdmintradores->setRoles([$roles]);
							
							$password = $passwordEncoder->encodePassword($oAdmintradores, $password_first);
							
							$oAdmintradores->setPassword($password);
							$oAdmintradores->setUpdatedAt($oDateHoy);
 
							try {

								$em->persist($oAdmintradores);
								$em->flush();

								$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
								return new JsonResponse($arrRespuesta);

							} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
								if($e->getErrorCode() == 1062){
									$errores = array(
										"nombre"         => "",
										"email"                  => "Ya existe el email",
										"roles"                  => "",
										"password"               => "",
										"password_second"        => "",
									);

									$valores = array(
										"nombre"             => $nombre,
										"apellidos"          => $apellidos,
										"email"              => $username,
										"roles"              => $roles,
										"id_administrador"   => $id_administrador              
									);

									return $this->render('AdminBundle:Administradores:editar.html.twig',
										array(
											"errores"   => $errores,
											"valores"   => $valores
										)
									);
								}else{                          
									$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
									return new JsonResponse($arrRespuesta);
								}
							}
						}
						
						$errores = array(
						"nombre"     => "",
						"email"              => "",
						"roles"              => "",
						"password"           => "Las contraseñas no soy iguales",
						"password_second"    => "Las contraseñas no soy iguales"
					   );

						$valores = array(
							"nombre"             => $nombre,
							"apellidos"          => $apellidos,
							"email"              => $username,
							"roles"              => $roles,
							"id_administrador"   => $id_administrador 
						);

						return $this->render('AdminBundle:Administradores:editar.html.twig',
							array(
								"errores"   => $errores,
								"valores"   => $valores
							)
						);
					}
				}

				$errores = array(
				"nombre"     => "",
				"email"              => "Email inválido",
				"roles"              => "",
				"password"           => "",
				"password_second"    => ""
			   );

				$valores = array(
					"nombre"             => $nombre,
					"apellidos"          => $apellidos,
					"email"              => $username,
					"roles"              => $roles,
					"id_administrador"   => $id_administrador 
				);

				return $this->render('AdminBundle:Administradores:editar.html.twig',
					array(
						"errores"   => $errores,
						"valores"   => $valores
					)
				);
			}

			$errores = array(
				"nombre"             => ($nombre)?"":"El nombre es obligatorio",
				"email"              => ($username)?"":"El email es obligatorio",
				"roles"              => ($roles)?"":"Debe seleccionar un rol",
				"password"           => "",
				"password_second"    => ""
			);

			$valores = array(
				"nombre"             => $nombre,
				"apellidos"          => $apellidos,
				"email"              => $username,
				"roles"              => $roles,
				"id_administrador"   => $id_administrador              
			);

			return $this->render('AdminBundle:Administradores:editar.html.twig',
				array(
					"errores"   => $errores,
					"valores"   => $valores
				)
			);
		}

		$id_administrador        = $request->query->get('id_administrador');
		$oAdmintradores          = $em->getRepository('AdminBundle:Administradores')->find($id_administrador);

		$errores = array(
			"nombre"             => "",
			"email"              => "",
			"roles"              => "",
			"password"           => "",
			"password_second"    => ""
		);

		$valores = array(
			"nombre"             => $oAdmintradores->getNombre(),
			"apellidos"          => $oAdmintradores->getApellidos(),
			"email"              => $oAdmintradores->getUsername(),
			"roles"              => implode('', $oAdmintradores->getRoles()),
			"id_administrador"   => $id_administrador
		);

		return $this->render('AdminBundle:Administradores:editar.html.twig',
			array(
				"errores"   => $errores,
				"valores"   => $valores
			)
		);    
	}

	/**
	 * @Route("/admin/administradores/eliminar", options={"expose"=true}, name="admin_administradores_eliminar")
	*/
	public function eliminarAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		date_default_timezone_set("America/Santiago");

		$dateHoy  = date("Y-m-d H:i:s");
		$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
		
		$id_administrador       = $request->request->get('id_administrador');
		$oAdmintradores         = $em->getRepository('AdminBundle:Administradores')->find($id_administrador);
		
		$oAdmintradores->setIdEstadoUsuario($em->getReference('AdminBundle:EstadosUsuarios', 3));
		$oAdmintradores->setUpdatedAt($oDateHoy);
		
		$em->persist($oAdmintradores);
		$em->flush();

		$arrRespuesta = array("estado" => "success", "mensaje" => "El admin se ha eliminado correctamente.");
		return new JsonResponse($arrRespuesta);
	}

	/**
	 * @Route("/admin/administradores/bloquear", options={"expose"=true}, name="admin_administradores_bloquear")
	*/
	public function bloquearAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		date_default_timezone_set("America/Santiago");

		$dateHoy  = date("Y-m-d H:i:s");
		$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
		
		$id_administrador       = $request->request->get('id_administrador');
		$oAdmintradores         = $em->getRepository('AdminBundle:Administradores')->find($id_administrador);

		$oAdmintradores->setIdEstadoUsuario($em->getReference('AdminBundle:EstadosUsuarios', 2));
		$oAdmintradores->setUpdatedAt($oDateHoy);
		
		$em->persist($oAdmintradores);
		$em->flush();

		$arrRespuesta = array("estado" => "success", "mensaje" => "El admin se ha bloquear correctamente.");
		return new JsonResponse($arrRespuesta);
	}

	/**
	 * @Route("/admin/administradores/activar", options={"expose"=true}, name="admin_administradores_activar")
	*/
	public function activarAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		date_default_timezone_set("America/Santiago");

		$dateHoy  = date("Y-m-d H:i:s");
		$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
		
		$id_administrador       = $request->request->get('id_administrador');
		$oAdmintradores         = $em->getRepository('AdminBundle:Administradores')->find($id_administrador);

		$oAdmintradores->setIdEstadoUsuario($em->getReference('AdminBundle:EstadosUsuarios', 1));
		$oAdmintradores->setUpdatedAt($oDateHoy);
		
		$em->persist($oAdmintradores);
		$em->flush();

		$arrRespuesta = array("estado" => "success", "mensaje" => "El admin se ha activado correctamente.");
		return new JsonResponse($arrRespuesta);
	}
}
