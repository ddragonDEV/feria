<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use FeriaUC\AdminBundle\Entity\Usuarios;

class UsuariosController extends Controller
{
    /**
     * @Route("/admin/usuarios", options={"expose"=true}, name="admin_usuarios")
    */
    public function indexAction(){

    	$em = $this->getDoctrine()->getManager();
    	
        return $this->render('AdminBundle:Usuarios:index.html.twig');
    }

    /**
     * @Route("/admin/usuarios/datatables", options={"expose"=true}, name="admin_usuarios_datatables")
    */
    public function datatablesAction(Request $request){

    	$em = $this->getDoctrine()->getManager();

		$start  = $request->query->get('start');
		$length = $request->query->get('length');
		$columns = $request->query->get('columns');
		
		$dataTable = $this->container->get('datatable_server_side')->serverProcessingUsuarios($columns, $start, $length);

		return new JsonResponse($dataTable);
    }

    /**
	 * @Route("/admin/usuarios/agregar", options={"expose"=true}, name="admin_usuarios_agregar")
	*/
	public function agregarAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		$oVisitantes = $em->getRepository('AdminBundle:Visitantes')->findAll();

		$oRegiones = $em->getRepository('AdminBundle:Regiones')->findAll();

		$oColegios = $em->getRepository('AdminBundle:Colegios')->obtenerColegios();

		$oCarreras = $em->getRepository('AdminBundle:Carreras')->obtenerCarreras();

		if ($request->getMethod() === 'POST') {

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$passwordEncoder = $this->get('security.password_encoder');

			$nombre     = $request->request->get('nombre');
			$apellidos  = $request->request->get('apellidos');

			$rut =  $request->request->get('rut');
			$rut_format  =  str_replace('.', '', $rut);
			$rut_format  =  str_replace('-', '', $rut_format);

			$username         = $request->request->get('username');
			$telefono         = $request->request->get('telefono');
			$id_visitante     = $request->request->get('id_visitante');
			$option_visitante = $request->request->get('option_visitante');
			$id_region        = $request->request->get('id_region');
			$id_comuna        = $request->request->get('id_comuna');
			$id_colegio       = $request->request->get('id_colegio');
			$option_colegio   = $request->request->get('option_colegio');
			$carreras         = $request->request->get('carreras');
			$ensayo           = $request->request->get('ensayo');
			$avatar           = $request->request->get('avatar');


			if($nombre && $apellidos && $rut && $username && $telefono && $id_visitante && $id_region && $id_comuna && $id_colegio && $carreras && $ensayo && $avatar){

				if (filter_var($username, FILTER_VALIDATE_EMAIL)) {

						$oUsuarios = new Usuarios();

						$oUsuarios->setNombre($nombre);
				        $oUsuarios->setApellidos($apellidos);
				        $oUsuarios->setRut($rut_format);
				        $oUsuarios->setUsername($username);
				        $oUsuarios->setTelefono($telefono);

					    $password = $passwordEncoder->encodePassword($oUsuarios, substr($rut_format, 0, 4));
					    $oUsuarios->setPassword($password);

					    $oUsuarios->setIdEstadoUsuario($em->getReference('AdminBundle:EstadosUsuarios', 1));

					    $oUsuarios->setIdVisitante($em->getReference('AdminBundle:Visitantes', $id_visitante));
					    $oUsuarios->setOpcionVisitante($option_visitante);

					    $oUsuarios->setIdRegion($em->getReference('AdminBundle:Regiones', $id_region));
					    $oUsuarios->setIdComuna($em->getReference('AdminBundle:Comunas', $id_comuna));

					    $oUsuarios->setIdColegio($em->getReference('AdminBundle:Colegios', $id_colegio));
					    $oUsuarios->setOpcionColegio($option_colegio);

					    $oUsuarios->setCarreras(implode(',', $carreras));
					    $oUsuarios->setEnsayo($ensayo);
					    $oUsuarios->setImagenPerfil($avatar);
					    $oUsuarios->setCondiciones(1);

					    $oUsuarios->setPasswordMd5(md5(substr($rut_format, 0, 4)));

					    $oUsuarios->setcreatedAt($oDateHoy);

						try {
							$em->persist($oUsuarios);
							$em->flush();

							$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
							return new JsonResponse($arrRespuesta);

						} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
							if($e->getErrorCode() == 1062){
								$errores = array(
									"nombre"                 => "",
									"apellidos"              => "",
									"rut"                    => "El rut se encuentra ya registrado",
									"username"               => "",
									"telefono"               => "",
									"id_visitante"           => "",
									"id_region"              => "",
									"id_comuna"              => "",
									'id_colegio'             => "",
									'carreras'               => "",
									'ensayo'                 => "",
									"avatar"                 => "",

								);

								$valores = array(
									"nombre"             => $nombre,
									"apellidos"          => $apellidos,
									"rut"                => $rut,
									"username"           => $username,
									"telefono"           => $telefono,
									"telefono"           => $telefono,
									'id_visitante'       => $id_visitante,
									'option_visitante'   => $option_visitante,
									'id_region'          => $id_region,
									'id_comuna'          => $id_comuna,
									'id_colegio'         => $id_colegio,
									'option_colegio'     => $option_colegio,
									'carreras'           => $carreras,
									'ensayo'             => $ensayo,
									"avatar"             => $avatar,
								);

								return $this->render('AdminBundle:Usuarios:agregar.html.twig',
									array(
										"errores"     => $errores,
										"valores"     => $valores,
										"oVisitantes" => $oVisitantes,
										"oRegiones"   => $oRegiones,
										"oColegios"   => $oColegios,
										"oCarreras"   => $oCarreras
									)
								);
							}else{		            		
								$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
								return new JsonResponse($arrRespuesta);
							}
						}  
				
				}
					$errores = array(
						
				        "nombre"                 => "",
						"apellidos"              => "",
						"rut"                    => "",
						"username"               => "Email inválido",
						"telefono"               => "",
						"id_visitante"           => "",
						"id_region"              => "",
						"id_comuna"              => "",
						'id_colegio'             => "",
						'carreras'               => "",
						'ensayo'                 => "",
						"avatar"                 => "",
				      );

					$valores = array(
						"nombre"             => $nombre,
						"apellidos"          => $apellidos,
						"rut"                => $rut,
						"username"           => $username,
						"telefono"           => $telefono,
						'id_visitante'       => $id_visitante,
						'option_visitante'   => $option_visitante,
						'id_region'          => $id_region,
						'id_comuna'          => $id_comuna,
						'id_colegio'         => $id_colegio,
						'option_colegio'     => $option_colegio,
						'carreras'           => $carreras,
						'ensayo'             => $ensayo,
						"avatar"             => $avatar,
					);

					return $this->render('AdminBundle:Usuarios:agregar.html.twig',
						array(
							"errores"     => $errores,
							"valores"     => $valores,
							"oVisitantes" => $oVisitantes,
							"oRegiones"   => $oRegiones,
							"oColegios"   => $oColegios,
							"oCarreras"   => $oCarreras
						)
				    );
		   }

			$errores = array(
	
				"nombre"                 => ($nombre)?"":"El nombre es obligatorio",
				"apellidos"              => ($apellidos)?"":"Los apellidos es obligatorio",
				"rut"                    => ($rut)?"":"El rut es obligatorio",
				"username"               => ($username)?"":"El email es obligatorio",
				"telefono"               => ($telefono)?"":"El teléfono es obligatorio",
				"id_visitante"           => ($id_visitante)?"":"La visita es obligatoria",
				"id_region"              => ($id_region)?"":"La región es obligatoria",
				"id_comuna"              => ($id_comuna)?"":"La comuna es obligatoria",
				'id_colegio'             => ($id_colegio)?"":"El colegio es obligatorio",
				'carreras'               => ($carreras)?"":"debe por lo menor seleccionar una",
				'ensayo'                 => ($ensayo)?"":"El ensayo es obligatorio",
				"avatar"                 => ($avatar)?"":"Debe seleccionar un avatar",
			);

			$valores = array(
				"nombre"             => $nombre,
				"apellidos"          => $apellidos,
				"rut"                => $rut,
				"username"           => $username,
				"telefono"           => $telefono,
				'id_visitante'       => $id_visitante,
				'option_visitante'   => $option_visitante,
				'id_region'          => $id_region,
				'id_comuna'          => $id_comuna,
				'id_colegio'         => $id_colegio,
				'option_colegio'     => $option_colegio,
				'carreras'           => $carreras,
				'ensayo'             => $ensayo,
				"avatar"             => $avatar,

			);

			return $this->render('AdminBundle:Usuarios:agregar.html.twig',
				array(
					"errores"     => $errores,
					"valores"     => $valores,
					"oVisitantes" => $oVisitantes,
					"oRegiones"   => $oRegiones,
					"oColegios"   => $oColegios,
					"oCarreras"   => $oCarreras
				)
			);
		}

		$errores = array(
		   "nombre"                  => "",
			"apellidos"              => "",
			"rut"                    => "",
			"username"               => "",
			"telefono"               => "",
			"id_visitante"           => "",
			"id_region"              => "",
			"id_comuna"              => "",
			'id_colegio'             => "",
			'carreras'               => "",
			'ensayo'                 => "",
			"avatar"                 => "",
		);

		$valores = array(
		    "nombre"             => "",
			"apellidos"          => "",
			"rut"                => "",
			"username"           => "",
			"telefono"           => "",
			'id_visitante'       => "",
			'option_visitante'   => "",
			'id_region'          => "",
			'id_comuna'          => "",
			'id_colegio'         => "",
			'option_colegio'     => "",
			'carreras'           => "",
			'ensayo'             => "",
			"avatar"             => "",
		);

		return $this->render('AdminBundle:Usuarios:agregar.html.twig',
			array(
				"errores"     => $errores,
				"valores"     => $valores,
				"oVisitantes" => $oVisitantes,
				"oRegiones"   => $oRegiones,
				"oColegios"   => $oColegios,
				"oCarreras"   => $oCarreras
			)
		);	
	}

    /**
	 * @Route("/admin/usuarios/editar", options={"expose"=true}, name="admin_usuarios_editar")
	*/
	public function editarAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		$oVisitantes = $em->getRepository('AdminBundle:Visitantes')->findAll();

		$oRegiones = $em->getRepository('AdminBundle:Regiones')->findAll();

		$oColegios = $em->getRepository('AdminBundle:Colegios')->obtenerColegios();

		$oCarreras = $em->getRepository('AdminBundle:Carreras')->obtenerCarreras();	
		
		if ($request->getMethod() === 'POST') {

			$id_usuario = $request->request->get('id_usuario');
			$oUsuarios  = $em->getRepository('AdminBundle:Usuarios')->find($id_usuario);

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
			
			$nombre           = $request->request->get('nombre');
			$apellidos        = $request->request->get('apellidos');
			$rut              = $request->request->get('rut');
			$username         = $request->request->get('username');
			$telefono         = $request->request->get('telefono');
			$id_visitante     = $request->request->get('id_visitante');
			$option_visitante = $request->request->get('option_visitante');
			$id_region        = $request->request->get('id_region');
			$id_comuna        = $request->request->get('id_comuna');
			$id_colegio       = $request->request->get('id_colegio');
			$option_colegio   = $request->request->get('option_colegio');
			$carreras         = $request->request->get('carreras');
			$ensayo           = $request->request->get('ensayo');
			$avatar           = $request->request->get('avatar');

			if($nombre && $apellidos && $rut && $username && $telefono && $id_visitante && $id_region && $id_comuna && $id_colegio && $carreras && $ensayo && $avatar){
				if (filter_var($username, FILTER_VALIDATE_EMAIL)) {

					    $oUsuarios->setNombre($nombre);
				        $oUsuarios->setApellidos($apellidos);
				        $oUsuarios->setUsername($username);
				        $oUsuarios->setTelefono($telefono);

					    $oUsuarios->setIdVisitante($em->getReference('AdminBundle:Visitantes', $id_visitante));
					    $oUsuarios->setOpcionVisitante($option_visitante);

					    $oUsuarios->setIdRegion($em->getReference('AdminBundle:Regiones', $id_region));
					    $oUsuarios->setIdComuna($em->getReference('AdminBundle:Comunas', $id_comuna));

					    $oUsuarios->setIdColegio($em->getReference('AdminBundle:Colegios', $id_colegio));
					    $oUsuarios->setOpcionColegio($option_colegio);

					    $oUsuarios->setCarreras(implode(',', $carreras));
					    $oUsuarios->setEnsayo($ensayo);
					    $oUsuarios->setImagenPerfil($avatar);
					    $oUsuarios->setUpdatedAt($oDateHoy);

					    try {
							$em->persist($oUsuarios);
							$em->flush();

							$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
							return new JsonResponse($arrRespuesta);

						} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

							$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
							return new JsonResponse($arrRespuesta);
						}
				}

					$errores = array(
							
				        "nombre"                 => "",
						"apellidos"              => "",
						"rut"                    => "",
						"username"               => "Email inválido",
						"telefono"               => "",
						"id_visitante"           => "",
						"id_region"              => "",
						"id_comuna"              => "",
						'id_colegio'             => "",
						'carreras'               => "",
						'ensayo'                 => "",
						"avatar"                 => "",
			        );

			        $valores = array(
					"nombre"             => $nombre,
					"apellidos"          => $apellidos,
					"rut"                => $rut,
					"username"           => $username,
					"telefono"           => $telefono,
					'id_visitante'       => $id_visitante,
					'option_visitante'   => $option_visitante,
					'id_region'          => $id_region,
					'id_comuna'          => $id_comuna,
					'id_colegio'         => $id_colegio,
					'option_colegio'     => $option_colegio,
					'carreras'           => $carreras,
					'ensayo'             => $ensayo,
					"avatar"             => $avatar,
					'id_usuario'         => $id_usuario 
				);

				return $this->render('AdminBundle:Usuarios:editar.html.twig',
					array(
						"errores"     => $errores,
						"valores"     => $valores,
						"oVisitantes" => $oVisitantes,
						"oRegiones"   => $oRegiones,
						"oColegios"   => $oColegios,
						"oCarreras"   => $oCarreras
					)
				 );
			}
			$errores = array(
	
				"nombre"                 => ($nombre)?"":"El nombre es obligatorio",
				"apellidos"              => ($apellidos)?"":"Los apellidos es obligatorio",
				"rut"                    => ($rut)?"":"El rut es obligatorio",
				"username"               => ($username)?"":"El email es obligatorio",
				"telefono"               => ($telefono)?"":"El teléfono es obligatorio",
				"id_visitante"           => ($id_visitante)?"":"La visita es obligatoria",
				"id_region"              => ($id_region)?"":"La región es obligatoria",
				"id_comuna"              => ($id_comuna)?"":"La comuna es obligatoria",
				'id_colegio'             => ($id_colegio)?"":"El colegio es obligatorio",
				'carreras'               => ($carreras)?"":"debe por lo menor seleccionar una",
				'ensayo'                 => ($ensayo)?"":"El ensayo es obligatorio",
				"avatar"                 => ($avatar)?"":"Debe seleccionar un avatar",
			);

			$valores = array(
				"nombre"             => $nombre,
				"apellidos"          => $apellidos,
				"rut"                => $rut,
				"username"           => $username,
				"telefono"           => $telefono,
				'id_visitante'       => $id_visitante,
				'option_visitante'   => $option_visitante,
				'id_region'          => $id_region,
				'id_comuna'          => $id_comuna,
				'id_colegio'         => $id_colegio,
				'option_colegio'     => $option_colegio,
				'carreras'           => $carreras,
				'ensayo'             => $ensayo,
				"avatar"             => $avatar,
				'id_usuario'         => $id_usuario 
			);

			return $this->render('AdminBundle:Usuarios:editar.html.twig',
				array(
					"errores"     => $errores,
					"valores"     => $valores,
					"oVisitantes" => $oVisitantes,
					"oRegiones"   => $oRegiones,
					"oColegios"   => $oColegios,
					"oCarreras"   => $oCarreras
				)
			);           
		}

		$id_usuario = $request->query->get('id_usuario');
		$oUsuarios  = $em->getRepository('AdminBundle:Usuarios')->find($id_usuario);

		
		$errores = array(
		   "nombre"                  => "",
			"apellidos"              => "",
			"rut"                    => "",
			"username"               => "",
			"telefono"               => "",
			"id_visitante"           => "",
			"id_region"              => "",
			"id_comuna"              => "",
			'id_colegio'             => "",
			'carreras'               => "",
			'ensayo'                 => "",
			"avatar"                 => "",
		);

		$valores = array(
		    "nombre"             => $oUsuarios->getNombre(),
			"apellidos"          => $oUsuarios->getApellidos(),
			"rut"                => $oUsuarios->getRut(),
			"username"           => $oUsuarios->getUsername(),
			"telefono"           => $oUsuarios->getTelefono(),
			'id_visitante'       => $oUsuarios->getIdVisitante()->getId(),
			'option_visitante'   => $oUsuarios->getOpcionVisitante(),
			'id_region'          => $oUsuarios->getIdRegion()->getId(),
			'id_comuna'          => $oUsuarios->getIdComuna()->getId(),
			'id_colegio'         => $oUsuarios->getIdColegio()->getId(),
			'option_colegio'     => $oUsuarios->getOpcionColegio(),
			'carreras'           => explode(',', $oUsuarios->getCarreras()),
			'ensayo'             => $oUsuarios->getEnsayo(),
			'avatar'             => $oUsuarios->getImagenPerfil(),
			'id_usuario'         => $id_usuario
		);

		return $this->render('AdminBundle:Usuarios:editar.html.twig',
			array(
				"errores"     => $errores,
				"valores"     => $valores,
				"oVisitantes" => $oVisitantes,
				"oRegiones"   => $oRegiones,
				"oColegios"   => $oColegios,
				"oCarreras"   => $oCarreras
			)
		); 
	}

	/**
     * @Route("/admin/usuarios/eliminar", options={"expose"=true}, name="admin_usuarios_eliminar")
    */
    public function eliminarAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        date_default_timezone_set("America/Santiago");

        $dateHoy  = date("Y-m-d H:i:s");
        $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
        
        $id_usuario = $request->request->get('id_usuario');
        $oUsuarios  = $em->getRepository('AdminBundle:Usuarios')->find($id_usuario);
        
        $oUsuarios->setIdEstadoUsuario($em->getReference('AdminBundle:EstadosUsuarios', 3));
        $oUsuarios->setUpdatedAt($oDateHoy);
        
        $em->persist($oUsuarios);
        $em->flush();

        $arrRespuesta = array("estado" => "success", "mensaje" => "El usuario se ha eliminado correctamente.");
        return new JsonResponse($arrRespuesta);
    }

    /**
     * @Route("/admin/usuarios/bloquear", options={"expose"=true}, name="admin_usuarios_bloquear")
    */
    public function bloquearAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        date_default_timezone_set("America/Santiago");

        $dateHoy  = date("Y-m-d H:i:s");
        $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
        
        $id_usuario = $request->request->get('id_usuario');
        $oUsuarios  = $em->getRepository('AdminBundle:Usuarios')->find($id_usuario);

        $oUsuarios->setIdEstadoUsuario($em->getReference('AdminBundle:EstadosUsuarios', 2));
        $oUsuarios->setUpdatedAt($oDateHoy);
        
        $em->persist($oUsuarios);
        $em->flush();

        $arrRespuesta = array("estado" => "success", "mensaje" => "El usuario se ha bloquear correctamente.");
        return new JsonResponse($arrRespuesta);
    }

    /**
     * @Route("/admin/usuarios/activar", options={"expose"=true}, name="admin_usuarios_activar")
    */
    public function activarAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        date_default_timezone_set("America/Santiago");

        $dateHoy  = date("Y-m-d H:i:s");
        $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
        
        $id_usuario = $request->request->get('id_usuario');
        $oUsuarios  = $em->getRepository('AdminBundle:Usuarios')->find($id_usuario);

        $oUsuarios->setIdEstadoUsuario($em->getReference('AdminBundle:EstadosUsuarios', 1));
        $oUsuarios->setUpdatedAt($oDateHoy);
        
        $em->persist($oUsuarios);
        $em->flush();

        $arrRespuesta = array("estado" => "success", "mensaje" => "El usuario se ha activado correctamente.");
        return new JsonResponse($arrRespuesta);
    }
    /**
	 * @Route("/admin/usuarios/obtener/comunas", options={"expose"=true}, name="admin_usuarios_obtener_comunas")
	*/
	public function obtenerComunasAction(Request $request){
    	$em = $this->getDoctrine()->getManager();

    	$id_region = $request->request->get('id_region');    	
        $oComunas = $em->getRepository('AdminBundle:Comunas')->findBy(array('idRegion' => $id_region), array('nombre' =>'ASC'));

        $aComunas = array();
        foreach ($oComunas as $comuna) {
        	array_push($aComunas, 
        		array(
        			'id'     => $comuna->getId(),
        			'nombre' => $comuna->getNombre()
        		)
        	);
        }
    	return new JsonResponse($aComunas);
    }

    /**
	 * @Route("/admin/usuarios/ver", options={"expose"=true}, name="admin_usuarios_ver")
	 */
	 public function verAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		$id_usuario   = $request->request->get('id_usuario');
		$oUsuarios    = $em->getRepository('AdminBundle:Usuarios')->find($id_usuario);

		$oCarreras = $em->getRepository('AdminBundle:Usuarios')->carrerasPorUsuario($oUsuarios->getCarreras());

		return $this->render('AdminBundle:Usuarios:ver.html.twig', 
			 array(
				'oUsuarios' => $oUsuarios,
				'oCarreras' => $oCarreras
			 )
		);
	 }


	/**
	 * @Route("/admin/usuarios/descargar/csv", options={"expose"=true}, name="admin_usuarios_descargar_csv")
	 */
	 public function descargarCSV(Request $request){
 
		$em = $this->getDoctrine()->getManager();

		$arrCSV = array();

		$fecha_inicio  = $request->request->get('fecha_inicio');
		$fecha_termino = $request->request->get('fecha_termino');

		$oFechaInicio  = new \DateTime($fecha_inicio);
		$oFechaTermino = new \DateTime($fecha_termino);
		$dFechaInicio  = $oFechaInicio->format('Y-m-d 00:00:00');
		$dFechaTermino = $oFechaTermino->format('Y-m-d 23:59:59');
		$oFechaInicio  = new \DateTime($dFechaInicio);
		$oFechaTermino = new \DateTime($dFechaTermino);

		$aUsuarios = $em->getRepository('AdminBundle:Usuarios')->obtenerUsuarios($oFechaInicio, $oFechaTermino);
		foreach ($aUsuarios as $usuario) {

			$carreras_novato = array();

			$aCarreras = $em->getRepository('AdminBundle:Usuarios')->carrerasPorUsuario($usuario['carreras']);
			foreach ($aCarreras as  $carrera) {
				$carreras_novato [] = $carrera['nombre'];
			}

			$opcion_vistente = $usuario['opcionVisitante'] ? $usuario['opcionVisitante']:'No aplica';
			$option_colegio  = $usuario['opcionColegio'] ?   $usuario['opcionColegio']:'No aplica';
			
			$sesion = $usuario['numero_sesion'] ? $usuario['numero_sesion'] : 0;

			$condicion = $usuario['condiciones'] ? 'Acepto':'No acepto';

			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

		    $domain = $_SERVER['HTTP_HOST'];

			$avatar = $protocol.$domain.'/web/bundles/app/novato/avatars/'.$usuario['imagenPerfil'];

			$arrCSV[] = array(
			    $usuario['id'],1,$usuario['rut'],$usuario['nombre'],$usuario['apellidos'],$usuario['username'],$usuario['password'], $usuario['telefono'],$usuario['region'],$usuario['comuna'],$usuario['visita'],$opcion_vistente,$usuario['colegio'],$option_colegio,implode(',', $carreras_novato),$usuario['ensayo'],$condicion, $avatar,'["ROLE_FERIA_UC_NOVATO"]',$usuario['password_md5'],$usuario['createdAt']->format('d-m-Y H:i'),$usuario['createdAt']->format('d-m-Y H:i'), $sesion);
		}

		$csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject);
		$csv->setDelimiter(';');
		$csv->insertOne([
		    'Id','Id Estado Usuario','Rut','Nombre','Apellidos','Correo','Password','Teléfono','Región','Comuna','visitante','Opcion del visitante','Colegio','Opcion del colegio','Carreras','Ensayo','Condición','Avatar','Roles','Password MD5','Fecha creación','Fecha Actualización','numero_sesion'



		    
		 ]);
		$csv->insertAll($arrCSV);

		return new Response(utf8_decode($csv), 200, [
			'Content-Encoding'    => 'none',
			'Content-Type'        => 'text/csv; charset=UTF-8',
			'Content-Disposition' => 'attachment; filename="usuarios.csv"',
			'Content-Description' => 'File Transfer',
		]);
	}
}
