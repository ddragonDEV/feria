<?php

namespace FeriaUC\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use FeriaUC\AdminBundle\Entity\Usuarios;
use FeriaUC\AdminBundle\Entity\ChatsMensajes;

use FeriaUC\AppBundle\MobileDetect\MobileDetect;

class DefaultController extends Controller
{
	/**
	 * @Route("/", options={"expose"=true}, name="inicio")
	 */
	public function indexAction(Request $request){

		if($this->getUser()->getNumeroSesion() == 0){
			return $this->redirectToRoute('perfil');
		}

		$em = $this->getDoctrine()->getManager();

		$path = $this->get('kernel')->getRootDir() . '/../web/mapa/svg/';

		$detect =  new MobileDetect();
		if ($detect->isMobile() || $detect->isTablet()) {
			$mobile = true;
		} else {
			$mobile = false;
		}

		$bodyclass = array('loading', 'has--instrucciones', 'debugg', 'markers--hidden');
		if ($mobile) { 
			$bodyclass[] = 'mobile'; 
		} else { 
			$bodyclass[] = 'desktop'; 
		}
		$bodyclasss = implode(' ', $bodyclass);

		$cursor_a = file_get_contents($path.'cursor_a.svg');
		$cursor_b = file_get_contents($path.'cursor_b.svg');

		$logo_uc    = file_get_contents($path.'logo_uc.svg');
		$logo_feria = file_get_contents($path.'logo_feria.svg');


		$oPuntos = $em->getRepository('AdminBundle:Puntos')->findBy(array('idEstado' => 1));
		$markers = '';
		$i = 1;
		foreach ($oPuntos as $key => $punto) {
			$markers .= $this->marker($i, '', $punto->getLeft(), $punto->getTop(), $punto->getIdTipoPunto()->getId()==2?"carreras":"normal", $punto->getDireccion(), array($punto->getTitulo(), $punto->getSubTitulo()), $punto->getIdTipoPunto()->getId()==2?"":"no--menu", $punto->getId(), $punto->getIcono());
			$i++;
		}

		// echo '<pre>';var_dump($bodyclasss);exit;
		return $this->render('AppBundle:Default:index.html.twig',
			array(
				'bodyclasss' => $bodyclasss,
				'cursor_a'   => $cursor_a,
				'cursor_b'   => $cursor_b,
				'logo_uc'    => $logo_uc,
				'logo_feria' => $logo_feria,
				'markers'    => $markers,
				'mobile'     => $mobile,
				'pointer'     => 0

			)
		);
	}

	/**
	 * @Route("/search/{slug}", options={"expose"=true}, name="friendly_url")
	 */
	public function slugAction(Request $request){

		if($this->getUser()->getNumeroSesion() == 0){
			return $this->redirectToRoute('perfil');
		}

		$em = $this->getDoctrine()->getManager();

		$path = $this->get('kernel')->getRootDir() . '/../web/mapa/svg/';

		$detect =  new MobileDetect();
		if ($detect->isMobile() || $detect->isTablet()) {
			$mobile = true;
		} else {
			$mobile = false;
		}

		$bodyclass = array('debugg');
		if ($mobile) { 
			$bodyclass[] = 'mobile'; 
		} else { 
			$bodyclass[] = 'desktop'; 
		}
		$bodyclasss = implode(' ', $bodyclass);

		$cursor_a = file_get_contents($path.'cursor_a.svg');
		$cursor_b = file_get_contents($path.'cursor_b.svg');

		$logo_uc    = file_get_contents($path.'logo_uc.svg');
		$logo_feria = file_get_contents($path.'logo_feria.svg');

		$slug =  $request->get('slug');

		$pointer = $em->getRepository('AdminBundle:Puntos')->findOneBy(array('slug' => $slug));

		if ($pointer!=null) {
			$pointer_id = $pointer->getId();
		}else{
			$pointer_id = 0;
		}


		$oPuntos = $em->getRepository('AdminBundle:Puntos')->findBy(array('idEstado' => 1));

		$markers = '';
		$i = 1;
		foreach ($oPuntos as $key => $punto) {
			$markers .= $this->marker($i, '', $punto->getLeft(), $punto->getTop(), $punto->getIdTipoPunto()->getId()==2?"carreras":"normal", $punto->getDireccion(), array($punto->getTitulo(), $punto->getSubTitulo()), $punto->getIdTipoPunto()->getId()==2?"":"no--menu", $punto->getId(), $punto->getIcono());
			$i++;
		}


		return $this->render('AppBundle:Default:index.html.twig',
			array(
				'bodyclasss' => $bodyclasss,
				'cursor_a'   => $cursor_a,
				'cursor_b'   => $cursor_b,
				'logo_uc'    => $logo_uc,
				'logo_feria' => $logo_feria,
				'markers'    => $markers,
				'mobile'     => $mobile,
				'pointer'     => $pointer_id
			)
		);
	}

	/**
	 * @Route("/formulario-registro", options={"expose"=true}, name="formulario_registro")
	 */
	public function registrarmeAction(Request $request){
		$em = $this->getDoctrine()->getManager();

		$oVisitantes = $em->getRepository('AdminBundle:Visitantes')->findAll();
		$oRegiones   = $em->getRepository('AdminBundle:Regiones')->findAll();
		$oColegios   = $em->getRepository('AdminBundle:Colegios')->obtenerColegios();
		$oCarreras   = $em->getRepository('AdminBundle:Carreras')->obtenerCarreras();

		$path = $this->get('kernel')->getRootDir() . '/../web/novato/svg/';

		$puc  = file_get_contents($path.'logo_uc.svg');
		$logo = file_get_contents($path.'logo_feria.svg');

		$bg_in = '';

		$orbitas = array(
			array('2', 85, true, false, false, 100, 100, false),
			array('3', 67, true, true, false, 100, 100, false),
			array('1', 91, true, true, false, 100, 100, false),
			array('4', 110, true, true, false, 100, 100, false),
			array('1', 87, false, true, true, 90, 100, true),
			array('2', 100, false, true, true, 100, 100, true),
			array('3', 59, false, true, true, 100, 100, true),
			array('4', 73, false, true, true, 89, 100, true),
			array('6', 65, false, true, true, 55, 100, false),
			array('9', 53, false, true, true, 70, 300, false),
			array('5', 88, false, true, true, 32, 100, false),
			array('7', 61, false, true, true, 50, 600, false),
			array('8', 80, false, true, true, 50, 450, false),
			array('10', 54, false, true, false, 18, 100, false),
			array('10', 97, false, true, false, 12, 100, false),
			array('11', 82, false, true, false, 11, 100, false),
			array('11', 62, false, true, false, 17, 100, false),
		);

		shuffle($orbitas);
		foreach($orbitas as $o) {
			$bg_in .= $this->orbita($o[0], $o[1], $o[2], $o[3], $o[4], $o[5], $o[6], $o[7]);
		}

		return $this->render('AppBundle:Default:registrarme.html.twig', 
			array(
				'puc'         => $puc,
				'logo'        => $logo,
				'bg_in'       => $bg_in,
				'oVisitantes' => $oVisitantes,
				'oRegiones'   => $oRegiones,
				'oColegios'   => $oColegios,
				'oCarreras'   => $oCarreras
			)
		);
	}

	/**
	 * @Route("/formulario-registro/agregar", options={"expose"=true}, name="formulario_registro_agregar")
	*/
	public function agregarAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		date_default_timezone_set("America/Santiago");

		$dateHoy  = date("Y-m-d H:i:s");
		$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

		$passwordEncoder = $this->get('security.password_encoder');

		$nombre     = $request->request->get('nombre');
		$apellidos  = $request->request->get('apellidos');

		$rut =  $request->request->get('rut');
		$rut =  str_replace('.', '', $rut);
		$rut =  str_replace('-', '', $rut);

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
		$condicion        = $request->request->get('condicion');

		if($nombre && $apellidos && $rut && $username && $telefono && $id_visitante && $id_region && $id_comuna && $id_colegio && $carreras && $ensayo && $condicion){

			if (filter_var($username, FILTER_VALIDATE_EMAIL)) {

				$oUsuarios = new Usuarios();

				$oUsuarios->setNombre($nombre);
				$oUsuarios->setApellidos($apellidos);
				$oUsuarios->setRut($rut);
				$oUsuarios->setUsername($username);
				$oUsuarios->setTelefono($telefono);

				$password = $passwordEncoder->encodePassword($oUsuarios, substr($rut, 0, 4));
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
				$oUsuarios->setCondiciones($condicion);

				$oUsuarios->setPasswordMd5(md5(substr($rut, 0, 4)));

				$oUsuarios->setcreatedAt($oDateHoy);

				try {
					$em->persist($oUsuarios);
					$em->flush();
					
					//API URL
    $fields = array('to' => $username);
    $fields_string = http_build_query($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://feriadeorientacion.uc.cl:5000/v1/text-mail");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string );
    $data = curl_exec($ch);
    curl_close($ch);

                          
					$arrRespuesta = array("estado" => "OK", "mensaje" => "<p>¡Felicidades estás inscrito/a! Te esperamos del 11 al 15 de Febrero en la Feria de Orientación y Postulación UC.</p>¡Recuerda! Para ingresar, tu usuario es el RUT y tu contraseña son los 4 primeros dígitos del RUT.", "datos" => array("id_usuario" => $oUsuarios->getId(), "rut" => $rut, "nombre" => $nombre." ".$apellidos,"avatar" => "foto-perfil-".rand(1, 22).".png"));
					return new JsonResponse($arrRespuesta);

				} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

					if($e->getErrorCode() == 1062){
						$arrRespuesta = array("estado" => "ERROR", "mensaje" => "El rut se encuentra ya registrado.");
					} else{
						$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos.");
					}
					return new JsonResponse($arrRespuesta);	
				}
			}
			$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Email inválido.");
			return new JsonResponse($arrRespuesta);
		}
		$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Debe llenar todos los campos obligatorios (*)");
		return new JsonResponse($arrRespuesta);	
	}

    /**
    * @Route("/login", options={"expose"=true}, name="login")
    */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils){
    	$errors       = $authenticationUtils->getLastAuthenticationError();
    	$lastUsername = $authenticationUtils->getLastUsername();

    	$path = $this->get('kernel')->getRootDir() . '/../web/novato/svg/';

    	$puc  = file_get_contents($path.'logo_uc.svg');
    	$logo = file_get_contents($path.'logo_feria.svg');

    	$bg_in = '';

    	$orbitas = array(
    		array('2', 85, true, false, false, 100, 100, false),
    		array('3', 67, true, true, false, 100, 100, false),
    		array('1', 91, true, true, false, 100, 100, false),
    		array('4', 110, true, true, false, 100, 100, false),
    		array('1', 87, false, true, true, 90, 100, true),
    		array('2', 100, false, true, true, 100, 100, true),
    		array('3', 59, false, true, true, 100, 100, true),
    		array('4', 73, false, true, true, 89, 100, true),
    		array('6', 65, false, true, true, 55, 100, false),
    		array('9', 53, false, true, true, 70, 300, false),
    		array('5', 88, false, true, true, 32, 100, false),
    		array('7', 61, false, true, true, 50, 600, false),
    		array('8', 80, false, true, true, 50, 450, false),
    		array('10', 54, false, true, false, 18, 100, false),
    		array('10', 97, false, true, false, 12, 100, false),
    		array('11', 82, false, true, false, 11, 100, false),
    		array('11', 62, false, true, false, 17, 100, false),
    	);

    	shuffle($orbitas);
    	foreach($orbitas as $o) {
    		$bg_in .= $this->orbita($o[0], $o[1], $o[2], $o[3], $o[4], $o[5], $o[6], $o[7]);
    	}

    	return $this->render('AppBundle:Default:login.html.twig',
    		array(
    			'errors'   => $errors,
    			'username' => $lastUsername,			
    			'puc'      => $puc,
    			'logo'     => $logo,
    			'bg_in'    => $bg_in			
    		)
    	);
    }

    /**
	 * @Route("/logout", name="logout")
	*/
    public function logoutAction(){}

	/**
	 * @Route("/formulario-registro/obtener-comunas", options={"expose"=true}, name="obtener_comunas")
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
	 * @Route("/mapa/marker-obtener-stands", options={"expose"=true}, name="mapa_marker_obtener_stands")
	*/
	public function markerObtenerStandsAction(Request $request){
		$em = $this->getDoctrine()->getManager();

		$arrCarreras = array();

		$idPunto = $request->query->get('idPunto');

		$oCaterias = $em->getRepository('AdminBundle:Categorias')->findBY(array(),array('orden' => 'ASC'));

		foreach ($oCaterias as $Categoria) {
			$oStands = $em->getRepository('AdminBundle:Stands')->obtenerStandsPorCategoria($idPunto, $Categoria->getId());
			foreach ($oStands as  $stand) {
				$arrCarreras[$Categoria->getNombre()][] = array('id'=>$stand['id'], 'nombre'=>$stand['nombre']);
			}
		}
		// $oStands = $em->getRepository('AdminBundle:Stands')->findBy(array("idPunto" => $idPunto));

		// $stands = array();
		// foreach ($oStands as  $stand) {
		// 	$stands[] = array(
		// 		"id"     => $stand->getId(),
		// 		"nombre" => $stand->getNombre()
		// 	);
		// }

		return $this->render('AppBundle:Default:listar-stands.html.twig',
			array(
				"arrCarreras" => $arrCarreras
			)
		);
	}

	/**
	 * @Route("/mapa/marker-obtener-informacion", options={"expose"=true}, name="mapa_marker_obtener_informacion")
	*/
	public function markerObtenerInformacionAction(Request $request){
		$em = $this->getDoctrine()->getManager();

		$arrGaleria = array();
		$arrVideios = array();

		$id   = $request->query->get('id');
		$tipo = $request->query->get('tipo');

		$titulo           = "";
		$contenido        = "";
		$imagen_principal = "";
		$malla_curricular = "";
		if($tipo == 'carreras'){
			$oStands          = $em->getRepository('AdminBundle:Stands')->find($id);
			// echo $oStands->getImagenPrincipal();exit;
			$titulo           = $oStands->getNombre();
			$imagen_principal = 'uploads/stands/imagen_principal/'.$oStands->getImagenPrincipal();
			$contenido        = $oStands->getInfoGeneral();
			$instagram        = $oStands->getUrlInstagram();
			$facebook         = $oStands->getUrlFacebook();
			$youtube          = $oStands->getUrlYoutube();
			$web              = $oStands->getUrlWeb();
			$malla_curricular = $oStands->getMallaCurricular();

			$oRelEstandMultimedia = $em->getRepository('AdminBundle:RelEstandMultimedia')->findBy(array('idStand' =>$id));

			foreach ($oRelEstandMultimedia as  $multi) {
				$id_multi = $multi->getIdContenidoMultimedia()->getId();
				$oContenidoMultimedia = $em->getRepository('AdminBundle:ContenidoMultimedia')->find($id_multi);

				// $arrGaleria [] =  array('img' => 'uploads/stands/multimedias/'.$id.'/'.$oContenidoMultimedia->getUrl(),
			    // 'codigo_youtube' => $oContenidoMultimedia->getCodigoYoutube());

				if($oContenidoMultimedia->getIdTipoMultimedia()->getId() == 1 && 
					$oContenidoMultimedia->getIdEstadoMultimedia()->getId() == 1){
					$arrGaleria [] =  array('img' => 'uploads/stands/multimedias/'.$id.'/'.$oContenidoMultimedia->getUrl()) ;
			}

			else if($oContenidoMultimedia->getIdTipoMultimedia()->getId() == 2 &&
				$oContenidoMultimedia->getIdEstadoMultimedia()->getId() == 1){
				$arrVideios [] =  array('img' => 'uploads/stands/multimedias/'.$id.'/'.$oContenidoMultimedia->getUrl(),
					'codigo_youtube' => $oContenidoMultimedia->getCodigoYoutube());
		}
	}
}


if($tipo == 'normal'){
	$InfoPuntos       = $em->getRepository('AdminBundle:InfoPuntos')->findOneBy(array("idPunto" => $id ));
	$titulo           = $InfoPuntos->getIdPunto()->getTitulo();
	$imagen_principal = 'uploads/puntos/imagen_principal/'.$InfoPuntos->getIdPunto()->getImagenPrincipal();
	$contenido        = $InfoPuntos->getInfoGeneral();
	$instagram        = $InfoPuntos->getUrlInstagram();
	$facebook         = $InfoPuntos->getUrlFacebook();
	$youtube          = $InfoPuntos->getUrlYoutube();
	$web              = $InfoPuntos->getUrlWeb();

	$oRelPuntoMultimedia = $em->getRepository('AdminBundle:RelPuntoMultimedia')->findBy(array('idPunto' =>$id));

	foreach ($oRelPuntoMultimedia as  $multi) {
		$id_multi = $multi->getIdContenidoMultimedia()->getId();
		$oContenidoMultimedia = $em->getRepository('AdminBundle:ContenidoMultimedia')->find($id_multi);

		if($oContenidoMultimedia->getIdTipoMultimedia()->getId() == 1 && 
			$oContenidoMultimedia->getIdEstadoMultimedia()->getId() == 1){
			$arrGaleria [] =  array('img' => 'uploads/puntos/multimedias/'.$id.'/'.$oContenidoMultimedia->getUrl()) ;
	}
	else if($oContenidoMultimedia->getIdTipoMultimedia()->getId() == 2 &&
		$oContenidoMultimedia->getIdEstadoMultimedia()->getId() == 1){
		$arrVideios [] =  array('img' => 'uploads/puntos/multimedias/'.$id.'/'.$oContenidoMultimedia->getUrl(),
			'codigo_youtube' => $oContenidoMultimedia->getCodigoYoutube());
}
}

}
		// faltan las galerias
		// faltan videos
return $this->render('AppBundle:Default:mostrar-contenido.html.twig',
	array(
		"titulo"           => $titulo,
		"contenido"        => $contenido,
		"imagen_principal" => $imagen_principal,
		"instagram"        => $instagram,
		"facebook"         => $facebook,
		"youtube"          => $youtube,
		"arrGaleria"       => $arrGaleria,
		"arrVideios"       => $arrVideios,
		"malla_curricular" => $malla_curricular,
		"id"               => $id,
		"tipo"             => $tipo,
		"web"              => $web
	)
);
}

	/**
	 * @Route("/mapa/marker-obtener-charlas-all", options={"expose"=true}, name="mapa_marker_obtener_charlas_all")
	*/
	public function markerObtenerCharlasAllAction(Request $request){		
		$em = $this->getDoctrine()->getManager();

		date_default_timezone_set("America/Santiago");

		$dateHoy  = strtotime(date("Y-m-d H:i"));

		$FechaHoy  = strtotime(date("Y-m-d"));

		//$arrCronogramaHorario = array();

		$arrPresente = array();
		$arrFuturo   = array();
		$arrPasado    = array();		

		$aCharlas = $em->getRepository('AdminBundle:Charlas')->obtenerCharlasPorStand();

		if($aCharlas){

			foreach ($aCharlas as  $charla) {

				$fecha = strtotime($charla['fecha']->format('d-m-Y'));

				$fecha_inicio = strtotime($charla['fecha']->format('d-m-Y H:i'));

				$fecha_termino = $charla['fechaTermino'] ? strtotime($charla['fechaTermino']->format('d-m-Y H:i')): '';

				$class_charla = 'charla--pasada';
				$btn_texto    = 'Próximamente';

				if($dateHoy >= $fecha_inicio && $dateHoy <= $fecha_termino){
					$class_charla = 'charla--vivo';
					$btn_texto    = 'Transmitiendo en vivo';
				}
				if($dateHoy > $fecha_termino){
					$class_charla = 'charla--pasada';
					$btn_texto    = 'Ver repetición';
				}
				if($dateHoy < $fecha){
					$class_charla = 'charla--futura';
					$btn_texto    = 'Próximamente';
				}

				$hora  = $charla['fecha']->format('H:i');

				$arrContenido = array('hora' => $hora,'titulo' => $charla['titulo'], 'expositor' => $charla['expositor'], 'lugar' => $charla['lugar'], 'codigo_youtube' => $charla['codigo_youtube'], 'class_charla' => $class_charla, 'btn_texto' => $btn_texto, 'stand' =>$charla['stand']);

				//$arrCronogramaHorario[$fecha][] = $arrContenido;

				if($FechaHoy == $fecha){
					$arrPresente[$fecha][] = $arrContenido;
				}
				if($FechaHoy < $fecha){
					$arrFuturo[$fecha][] = $arrContenido;
				}
				if($FechaHoy > $fecha){
					$arrPasado[$fecha][] = $arrContenido;
				}
			}
		}

		return $this->render('AppBundle:Default:mostrar-charlas-all.html.twig', 
			array(
				//'arrCronogramaHorario' => $arrCronogramaHorario
				'arrPresente' => $arrPresente,
				'arrFuturo'   => $arrFuturo,
				'arrPasado'   => $arrPasado
			)
		);
	}

	/**
	 * @Route("/mapa/marker-obtener-charlas", options={"expose"=true}, name="mapa_marker_obtener_charlas")
	*/
	public function markerObtenerCharlasAction(Request $request){		
		$em = $this->getDoctrine()->getManager();

		date_default_timezone_set("America/Santiago");

		$dateHoy  = strtotime(date("Y-m-d H:i"));

		$arrCronogramaHorario = array();		

		$id   = $request->query->get('id');
		$tipo = $request->query->get('tipo');

		$aCharlas = $em->getRepository('AdminBundle:Charlas')->obtenerCharlasPorStand($id);

		if($aCharlas){

			foreach ($aCharlas as  $charla) {

				$fecha = strtotime($charla['fecha']->format('d-m-Y'));

				$fecha_inicio = strtotime($charla['fecha']->format('d-m-Y H:i'));

				$fecha_termino = $charla['fechaTermino'] ? strtotime($charla['fechaTermino']->format('d-m-Y H:i')): '';

				$class_charla = 'charla--pasada';
				$btn_texto    = 'Próximamente';
				
				if($dateHoy >= $fecha_inicio && $dateHoy <= $fecha_termino){
					$class_charla = 'charla--vivo';
					$btn_texto    = 'Transmitiendo en vivo';
				}
				if($dateHoy > $fecha_termino){
					$class_charla = 'charla--pasada';
					$btn_texto    = 'Ver repetición';
				}
				if($dateHoy < $fecha){
					$class_charla = 'charla--futura';
					$btn_texto    = 'Próximamente';
				}

				$hora  = $charla['fecha']->format('H:i');

				$arrContenido = array('hora' => $hora,'titulo' => $charla['titulo'], 'expositor' => $charla['expositor'], 'lugar' => $charla['lugar'], 'codigo_youtube' => $charla['codigo_youtube'], 'class_charla' => $class_charla, 'btn_texto' => $btn_texto, 'stand' =>$charla['stand']);

				$arrCronogramaHorario[$fecha][] = $arrContenido;
			}
		}
		//var_dump($arrCronogramaHorario);exit;

		$titulo           = "";
		$contenido        = "";
		$imagen_principal = "";
		if($tipo == 'charlas'){
			$oStands          = $em->getRepository('AdminBundle:Stands')->find($id);
			$titulo           = $oStands->getNombre();
		}

		return $this->render('AppBundle:Default:mostrar-charlas.html.twig',
			array(
				"titulo"               => $titulo,
				"id"                   => $id,
				"arrCronogramaHorario" => $arrCronogramaHorario
			)
		);
	}

	/**
	 * @Route("/mapa/marker-obtener-chat", options={"expose"=true}, name="mapa_marker_obtener_chat")
	*/
	public function markerObtenerChatAction(Request $request){		
		$em = $this->getDoctrine()->getManager();

		$id   = $request->query->get('id');
		$tipo = $request->query->get('tipo');

		return $this->render('AppBundle:Default:mostrar-chat.html.twig',
			array(
				"id" => $id
			)
		);
	}

	/**
	 * @Route("/mapa/marker-cargar-chat/{id_stand}", options={"expose"=true}, name="mapa_marker_cargar_chat")
	*/
	public function markerCargarChatAction(Request $request, $id_stand){			
		$em = $this->getDoctrine()->getManager();

		$usuarioId = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
		$nombre    = $this->container->get('security.token_storage')->getToken()->getUser()->getNombre()." ".$this->container->get('security.token_storage')->getToken()->getUser()->getApellidos();
		$avatar    = $this->container->get('security.token_storage')->getToken()->getUser()->getImagenPerfil();

		$oChats = $em->getRepository('AdminBundle:Chats')->findOneBy(array('idStand' => $id_stand));
		
		$aChats = array();
		if($oChats){
			//siempre carga el chat que esta preguntando
			$aChats[$oChats->getId()] = array(
				"id"     => $oChats->getId(),
				"nombre" => $oChats->getNombre(),
				"sin_responder" => 0
			);
		}

		//despues con los que ha conversado
		//falta el count
		$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->findBy(array('idUsuario' => $usuarioId));
		if($oChatsMensajes){
			foreach ($oChatsMensajes as $chatsMensajes) {
				$arrChatsMensajes = json_decode($chatsMensajes->getJsonMensajes());
				// echo '<pre>';var_dump($arrChatsMensajes);exit;
				$count = 0;
				foreach($arrChatsMensajes as $mensajes) {
					if ($mensajes->tipo == "embajador") {
						$count++;
					}
					if ($mensajes->tipo == "novato") {
						$count=0;
					}
				}
				$aChats[$chatsMensajes->getIdChat()->getId()] = array(
					"id"     => $oChats->getId(),
					"nombre" => $chatsMensajes->getIdChat()->getNombre(),
					"sin_responder" => $count
				);
			}
		}

		// si no hay mensajes muestra el chat pelado
		return $this->render('AppBundle:Default:cargar-chat.html.twig', array(
			"aChats"     => $aChats,
			"id_chat"    => $oChats->getId(),
			"id_usuario" => $usuarioId
		)
	);	
	}

	/**
	 * @Route("/mapa/marker-guardar-mensaje", options={"expose"=true}, name="mapa_marker_guardar_mensaje")
	 */
	public function guardarMensaje(Request $request){					
		$em = $this->getDoctrine()->getManager();

		$idChat        = $request->request->get('idChat');
		$idUsuario     = $request->request->get('idUsuario');
		$mensaje       = $request->request->get('mensaje');

		$usuarioId = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
		$nombre    = $this->container->get('security.token_storage')->getToken()->getUser()->getNombre()." ".$this->container->get('security.token_storage')->getToken()->getUser()->getApellidos();
		$avatar    = $this->container->get('security.token_storage')->getToken()->getUser()->getImagenPerfil();

		if($idChat && $idUsuario){
			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->findOneBy(array('idChat' => $idChat,'idUsuario' => $idUsuario));
			if($oChatsMensajes){
				$arrChatsMensajes  = json_decode($oChatsMensajes->getJsonMensajes());

				$arrChatsMensajes [] = array(
					'id'             => count($arrChatsMensajes) + 1,
					// 'nombre_usuario' => $nombre, 
					// 'avatar_usuario' => $avatar,
					'texto'          => $mensaje, 
					'timestamp'      => strtotime("now"),
					'tipo'           => 'novato'
				);

				$oChatsMensajes->setJsonMensajes(json_encode($arrChatsMensajes));
				$oChatsMensajes->setUpdatedAt($oDateHoy);

				$em->persist($oChatsMensajes);
				$em->flush();

				$arrRespuesta = array("estado" => "OK", "mensaje" => "Se han guardado los datos correctamente");
				return new JsonResponse($arrRespuesta);
			}else{
				$oChatsMensajes = new ChatsMensajes();

				$oChatsMensajes->setIdEstado($em->getReference('AdminBundle:Estados', 1));
				$oChatsMensajes->setIdUsuario($em->getReference('AdminBundle:Usuarios', $idUsuario));
				$oChatsMensajes->setIdChat($em->getReference('AdminBundle:Chats', $idChat));

				$arrChatsMensajes [] = array(
					'id'     => 1,
					'nombre' => $nombre, 
					'avatar' => $avatar,
					'texto'  => $mensaje, 
					'fecha'  => strtotime("now"),
					'tipo'   => 'novato'
				);

				$oChatsMensajes->setJsonMensajes(json_encode($arrChatsMensajes));
				$oChatsMensajes->setcreatedAt($oDateHoy);

				$em->persist($oChatsMensajes);
				$em->flush();

				$arrRespuesta = array("estado" => "OK", "mensaje" => "Se han guardado los datos correctamente");
				return new JsonResponse($arrRespuesta);
			}
		}

		$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un problema al intentar guardar los datos");
		return new JsonResponse($arrRespuesta);
	}

	/**
	 * @Route("/mapa/marker-obtener-chats", options={"expose"=true}, name="mapa_marker_obtener_chats")
	 */
	public function obtenerChatsAction(Request $request){					
		$em = $this->getDoctrine()->getManager();

		$id_chat = $request->request->get('id_chat');

		$usuarioId = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

		$oChats = $em->getRepository('AdminBundle:Chats')->find($id_chat);
		
		$aChats = array();
		if($oChats){
			$aChats[$oChats->getId()] = array(
				"id"            => $oChats->getId(),
				"nombre"        => $oChats->getNombre(),
				"sin_responder" => 0
			);
		}

		$oChatsMensajes = $em->getRepository('AdminBundle:ChatsMensajes')->findBy(array('idUsuario' => $usuarioId));		
		if($oChatsMensajes){
			foreach ($oChatsMensajes as $chatsMensajes) {
				$arrChatsMensajes = json_decode($chatsMensajes->getJsonMensajes());
				$count = 0;
				foreach($arrChatsMensajes as $mensajes) {
					if ($mensajes->tipo == "embajador") {
						$count++;
					}
					if ($mensajes->tipo == "novato") {
						$count=0;
					}
				}
				$aChats[$chatsMensajes->getIdChat()->getId()] = array(
					"id"            => $chatsMensajes->getIdChat()->getId(),
					"nombre"        => $chatsMensajes->getIdChat()->getNombre(),
					"sin_responder" => $count
				);
			}
		}

		// echo '<pre>';var_dump($aChats);exit;
		return $this->render('AppBundle:Default:listar-chats.html.twig', array(
			"aChats"     => $aChats,
			"id_usuario" => $usuarioId
		)
	);	
	}


	/**
	 * @Route("/perfil", options={"expose"=true}, name="perfil")
	*/
	public function avatarAction(Request $request){
		$em = $this->getDoctrine()->getManager();

		$path = $this->get('kernel')->getRootDir() . '/../web/novato/svg/';

		$puc  = file_get_contents($path.'logo_uc.svg');
		$logo = file_get_contents($path.'logo_feria.svg');

		$bg_in = '';

		$orbitas = array(
			array('2', 85, true, false, false, 100, 100, false),
			array('3', 67, true, true, false, 100, 100, false),
			array('1', 91, true, true, false, 100, 100, false),
			array('4', 110, true, true, false, 100, 100, false),
			array('1', 87, false, true, true, 90, 100, true),
			array('2', 100, false, true, true, 100, 100, true),
			array('3', 59, false, true, true, 100, 100, true),
			array('4', 73, false, true, true, 89, 100, true),
			array('6', 65, false, true, true, 55, 100, false),
			array('9', 53, false, true, true, 70, 300, false),
			array('5', 88, false, true, true, 32, 100, false),
			array('7', 61, false, true, true, 50, 600, false),
			array('8', 80, false, true, true, 50, 450, false),
			array('10', 54, false, true, false, 18, 100, false),
			array('10', 97, false, true, false, 12, 100, false),
			array('11', 82, false, true, false, 11, 100, false),
			array('11', 62, false, true, false, 17, 100, false),
		);

		shuffle($orbitas);
		foreach($orbitas as $o) {
			$bg_in .= $this->orbita($o[0], $o[1], $o[2], $o[3], $o[4], $o[5], $o[6], $o[7]);
		}

		if ($request->getMethod() === 'POST') {

			$id_usuario = $this->getUser()->getId();

			$oUsuarios  = $em->getRepository('AdminBundle:Usuarios')->find($id_usuario);


			$nombre      = $request->request->get('nombre');
			$apellidos   = $request->request->get('apellidos');
			$username    = $request->request->get('username');
			$avatar      = $request->request->get('avatar');

			if($avatar){

				if($nombre && $apellidos && $username){

					$oUsuarios->setNombre($nombre);
					$oUsuarios->setApellidos($apellidos);
					$oUsuarios->setUsername($username);
				}

				$oUsuarios->setImagenPerfil($avatar);
				$oUsuarios->setNumeroSesion(1);

				try {
					$em->persist($oUsuarios);
					$em->flush();

					$arrRespuesta = array("estado" => "OK", "mensaje" => "El avatar se han guardado correctamente", "datos" => array("id_usuario" => $oUsuarios->getId(), "rut" => $oUsuarios->getRut(), "nombre" => $oUsuarios->getNombre()." ".$oUsuarios->getApellidos(), "avatar" => $avatar));
					return new JsonResponse($arrRespuesta);
				} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

					$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
					return new JsonResponse($arrRespuesta);
				}
			}
			$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Debe selecionar un avatar");
			return new JsonResponse($arrRespuesta);
		}

		return $this->render('AppBundle:Default:avatar.html.twig', array(
			'puc'          => $puc,
			'logo'         => $logo,
			'bg_in'        => $bg_in
		)
	);
	}

	/**
	 * @Route("/ver/video/{codigo_youtube}", options={"expose"=true}, name="ver_video")
	*/
	public function markerVerVideoAction($codigo_youtube=null){

		if($codigo_youtube){

	  	// variable para obtenet el host del servidor con el objetivo de funcione el chat de youtube.
			$domain = $_SERVER["HTTP_HOST"];

			return $this->render('AppBundle:Default:ver-video.html.twig',array(
				'codigo_youtube' => $codigo_youtube,
				'domain'         => $domain
			)
		);
		}
		return $this->redirectToRoute('inicio');
	}


	/**
	 * @Route("/mapa/marker-obtener-nivel-2", options={"expose"=true}, name="mapa_marker_obtener_nivel_2")
	*/
	public function markerObtenerNivelAction(Request $request){
		$em = $this->getDoctrine()->getManager();

        // Para obtener los puntos del nivel 2.
		$oPuntos = $em->getRepository('AdminBundle:Puntos')->findBy(array('id' => [3,4,5,6,7,8,9,10,12,13,14,15,16]), array('titulo' =>'ASC'));
		
		return $this->render('AppBundle:Default:listar-nivel-2.html.twig', array('oPuntos' => $oPuntos));
	}




	private function orbita($name, $scale = 100, $is_path = false, $is_rotating = true, $is_still = true, $el_scale = 100, $acceleration = 100, $face = false) {
		$path = $this->get('kernel')->getRootDir() . '/../web/novato/svg/';

		$giro = 40;	
		$shuffleSpeed = $giro;
		if($is_path) { $type = 'path'; } else { $type = 'el'; }
		$r = '<div class="orbit is_' . $type; if($is_rotating) { $r .= ' rotates'; } $r .= '" style="'; if($scale != 100) { $r .= ' transform: scale(' . ($scale / 100) . ');'; } if($face) { $r .= ' z-index: 3;'; } $r .= '"><div class="' . $type; if($is_rotating) { $r .= ' rotating" style="animation-duration: ' . $shuffleSpeed . 's;'; } $r .= '"><div class="' . $type . '_in"><div class="' . $type . '_inn'; if($is_rotating && $is_still) { $r .= ' rotating reverse" style="animation-duration: ' . ($shuffleSpeed / ($acceleration / 100)) . 's;'; } $r .= '"><div class="' . $type . '_innn"'; if($type == 'el' && $el_scale != 100) { $r .= ' style="transform: scale(' . ($el_scale / 100) . ')"'; } $r .= '>' . file_get_contents($path . $type . '_' . $name . '.svg') . '</div></div></div></div></div>';
		
		return $r;
	}

	/**
	 * [marker description]
	 * @param  integer $index     [description]
	 * @param  string  $name      [description]
	 * @param  integer $left      son porcentajes
	 * @param  integer $top       son porcentajes
	 * @param  string  $type      [description]
	 * @param  string  $direction dirección del icono
	 * @param  array   $tooltip   titulo y subtitulo
	 * @param  string  $menu      [description]
	 * @return [type]             [description]
	 */
	private function marker($index = 0, $name = '', $left = 50, $top = 50, $type = 'stand', $direction = 'left', $tooltip = array('', ''), $menu = '', $idPunto, $icono) {
		$path = $this->get('kernel')->getRootDir() . '/../web/mapa/svg/curva--';
		if(!$name) { 
			$n = $index; 
		} else { 
			$n = $name; 
		}
		$r = '<div id="marker--' . $index . '" class="marker ' . $type . ' dir--' . $direction . '" style="left: ' . $left . '%; top: ' . $top . '%; z-index: ' . $top . ';"><button data-id="'.$idPunto.'" data-tipo="'.$type.'" class="btn--popup-' . $type . ' ' . $menu . '"><div class="sign"><span><img class="icono" src="/bundles/app/mapa/iconos-mapa/'.$icono.'"></span></div><div class="curva">' . file_get_contents($path . $direction . '.svg') . '<div class="circle"><b></b></div></div>'; if($tooltip[0] || $tooltop[1]) { $r .= '<div class="tooltip"><div><b></b><div>'; if($tooltip[0]) { $r .= '<div class="name h5">' . $tooltip[0] . '</div>'; } if($tooltip[1]) { $r .= '<div class="desc p-text--condensed p-color--gray">' . $tooltip[1] . '</div>'; } $r .= '</div></div></div>'; } $r .= '</button></div>';
		return $r;
	}
}
