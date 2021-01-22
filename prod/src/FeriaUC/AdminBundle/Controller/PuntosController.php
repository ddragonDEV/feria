<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use FeriaUC\AdminBundle\Entity\Puntos;
use FeriaUC\AdminBundle\Entity\InfoPuntos;

class PuntosController extends Controller
{
		/**
		 * @Route("/admin/puntos", options={"expose"=true}, name="admin_puntos")
		*/
		public function indexAction(){

			$em = $this->getDoctrine()->getManager();

			return $this->render('AdminBundle:Puntos:index.html.twig');
		}

	/**
     * @Route("/admin/puntos/datatables", options={"expose"=true}, name="admin_puntos_datatables")
    */
	public function datatablesAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		$start  = $request->query->get('start');
		$length = $request->query->get('length');
		$columns = $request->query->get('columns');
		
		$dataTable = $this->container->get('datatable_server_side')->serverProcessingPuntos($columns, $start, $length);

		return new JsonResponse($dataTable);
	}

		/**
		 * @Route("/admin/puntos/agregar", options={"expose"=true}, name="admin_puntos_agregar")
		*/
		public function agregarAction(Request $request){
			$em = $this->getDoctrine()->getManager();

			$mensaje = false;

			$ruta = $this->container->getparameter('kernel.root_dir').'/../web/uploads/puntos/imagen_principal/';

			$errores = array(
				"id_tipo_punto"    => "",
				"titulo"           => "",
				"direccion"        => "",
				"left"             => "",
				"top"              => "",
				"slug"             => "",
				"imagen_principal" => "",
				"icono"            => ""
			);

			$valores = array(
				"id_tipo_punto"      => 1,
				"titulo"             => "",
				"subtitulo"          => "",
				"direccion"          => "",
				"left"               => "",
				"top"                => "",
				"slug"               => "",
				"infoGeneral"        => "",
				"urlInstagram"       => "",
				"urlFacebook"        => "",
				"urlYoutube"         => "",
				"icono"              => "",
				"urlWeb"             => ""
			);

			$oTiposPunto = $em->getRepository('AdminBundle:TiposPunto')->findAll();

			if ($request->getMethod() === 'POST') {

				date_default_timezone_set("America/Santiago");

				$dateHoy  = date("Y-m-d H:i:s");
				$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

				$id_tipo_punto    = $request->request->get('id_tipo_punto');
				$titulo           = $request->request->get('titulo');
				$subtitulo        = $request->request->get('subtitulo');
				$direccion        = $request->request->get('direccion');
				$left             = $request->request->get('left');
				$top              = $request->request->get('top');
				$slug             = $request->request->get('slug');
				$imagen_principal = $request->files->get('imagen_principal');
				$icono            = $request->request->get('icono');
				$infoGeneral      = $request->request->get('infoGeneral');
				$urlInstagram     = $request->request->get('urlInstagram');
				$urlFacebook      = $request->request->get('urlFacebook');
				$urlYoutube       = $request->request->get('urlYoutube');
				$urlWeb           = $request->request->get('urlWeb');


				if($id_tipo_punto && $titulo  && $direccion && $left && $top && $slug){
					$oPuntos = new Puntos();

					$oPuntos->setIdTipoPunto($em->getReference('AdminBundle:TiposPunto', $id_tipo_punto));
					$oPuntos->setTitulo($titulo);
					$oPuntos->setSubTitulo($subtitulo);
					$oPuntos->setDireccion($direccion);
					$oPuntos->setLeft($left);
					$oPuntos->setTop($top);
					$oPuntos->setSlug($slug);
					$oPuntos->setIcono($icono);

					if( $id_tipo_punto == 2 ){
						$oPuntos->setMenu(1);	
					}

					$oPuntos->setIdEstado($em->getReference('AdminBundle:Estados', 1));
					$oPuntos->setCreatedAt($oDateHoy);

					try {

						$em->persist($oPuntos);
						$em->flush();

						if($id_tipo_punto == 1){

							if($imagen_principal){

								$nombreOriginal = $imagen_principal->getClientOriginalName();
								$temporal       = $imagen_principal->getPathName();
								$extension      = $imagen_principal->guessExtension();

								$destino        = $ruta."imagen_principal_".$oPuntos->getId().".".$extension;

								move_uploaded_file($temporal, $destino);

								$oPuntos->setImagenPrincipal("imagen_principal_".$oPuntos->getId().".".$extension);
								
								$em->persist($oPuntos);
								$em->flush();
							}

							$oInfoPuntos = new InfoPuntos();


							$oInfoPuntos->setInfoGeneral($infoGeneral);
							$oInfoPuntos->setUrlInstagram($urlInstagram);
							$oInfoPuntos->setUrlFacebook($urlFacebook);
							$oInfoPuntos->setUrlYoutube($urlYoutube);
							$oInfoPuntos->setUrlWeb($urlWeb);
							$oInfoPuntos->setIdPunto($oPuntos);

							$em->persist($oInfoPuntos);
							$em->flush();
						}

						$mensaje = true;

						return $this->render('AdminBundle:Puntos:agregar.html.twig', 
							array(
								'errores'     => $errores,
								'valores'     => $valores,
								'oTiposPunto' => $oTiposPunto,
								'mensaje'     => $mensaje
							)
						); 

					} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

						$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
						return new JsonResponse($arrRespuesta);
					}
				}

				$errores = array(
					"id_tipo_punto"    => $id_tipo_punto ? "":"El tipo de punto es obligatorio",
					"titulo"           => $titulo ? "":"El titulo es obligatorio",
					"direccion"        => $direccion ? "":"La dirección es obligatoria",
					"left"             => $left ? "":"El left es obligatorio",
					"top"              => $top ? "":"El top es obligatorio",
					"slug"              => $top ? "":"El slug o keyword es obligatorio",
					"imagen_principal" => ($id_tipo_punto==1)? ($imagen_principal ? "":"La imagen principal es obligatoria"):(""),
				);

				$valores = array(
					"id_tipo_punto"      => $id_tipo_punto,
					"titulo"             => $titulo,
					"subtitulo"          => $subtitulo,
					"direccion"          => $direccion,
					"left"               => $left,
					"top"                => $top,
					"slug"               => $slug,
					"icono"              => $icono,
					"infoGeneral"        => $infoGeneral,
					"urlInstagram"       => $urlInstagram,
					"urlFacebook"        => $urlFacebook,
					"urlYoutube"         => $urlYoutube,
					"urlWeb"             => $urlWeb
				);
			}

			return $this->render('AdminBundle:Puntos:agregar.html.twig', 
				array(
					'errores'     => $errores,
					'valores'     => $valores,
					'oTiposPunto' => $oTiposPunto,
					'mensaje'     => $mensaje
				)
			);  
		}

		/**
		 * @Route("/admin/puntos/editar/{id_punto}", options={"expose"=true}, name="admin_puntos_editar")
		*/
		public function editarAction(Request $request, $id_punto=null){

			$mensaje = false;

			$ruta = $this->container->getparameter('kernel.root_dir').'/../web/uploads/puntos/imagen_principal/';

			$em = $this->getDoctrine()->getManager();

			$oTiposPunto = $em->getRepository('AdminBundle:TiposPunto')->findAll();

			if ($request->getMethod() === 'POST') {

				date_default_timezone_set("America/Santiago");

				$dateHoy  = date("Y-m-d H:i:s");
				$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

				$id_punto = $request->request->get('id_punto');
				$oPuntos = $em->getRepository('AdminBundle:Puntos')->find($id_punto);

				$id_tipo_punto    = $request->request->get('id_tipo_punto');
				$titulo           = $request->request->get('titulo');
				$subtitulo        = $request->request->get('subtitulo');
				$direccion        = $request->request->get('direccion');
				$left             = $request->request->get('left');
				$top              = $request->request->get('top');
				$slug             = $request->request->get('slug');
				$icono            = $request->request->get('icono');

				$imagen_principal = $request->files->get('imagen_principal');

				$infoGeneral      = $request->request->get('infoGeneral');
				$urlInstagram     = $request->request->get('urlInstagram');
				$urlFacebook      = $request->request->get('urlFacebook');
				$urlYoutube       = $request->request->get('urlYoutube');
				$urlWeb           = $request->request->get('urlWeb');

				if($id_tipo_punto && $titulo  && $direccion && $left && $top && $slug){

					$oPuntos->setIdTipoPunto($em->getReference('AdminBundle:TiposPunto', $id_tipo_punto));
					$oPuntos->setTitulo($titulo);
					$oPuntos->setSubTitulo($subtitulo);
					$oPuntos->setDireccion($direccion);
					$oPuntos->setLeft($left);
					$oPuntos->setTop($top);
					$oPuntos->setSlug($slug);
					$oPuntos->setIcono($icono);

					$oPuntos->setMenu(0);

					if( $id_tipo_punto == 2 ){
						$oPuntos->setMenu(1);	
					}

					$oPuntos->setUpdatedAt($oDateHoy);

					try {

						$em->persist($oPuntos);
						$em->flush();

						if($imagen_principal){

							if(is_file($ruta.$oPuntos->getImagenPrincipal())){
								unlink($ruta.$oPuntos->getImagenPrincipal());
							}

							$nombreOriginal = $imagen_principal->getClientOriginalName();
							$temporal       = $imagen_principal->getPathName();
							$extension      = $imagen_principal->guessExtension();

							$destino        = $ruta."imagen_principal_".$oPuntos->getId().".".$extension;

							move_uploaded_file($temporal, $destino);

							$oPuntos->setImagenPrincipal("imagen_principal_".$oPuntos->getId().".".$extension);
							
							$em->persist($oPuntos);
							$em->flush();
						}

						$oInfoPuntos = $em->getRepository('AdminBundle:InfoPuntos')->findOneBy(array('idPunto' => $id_punto));

						if($id_tipo_punto == 1){

							if(!$oInfoPuntos){
								$oInfoPuntos = new InfoPuntos();
							}

							$oInfoPuntos->setInfoGeneral($infoGeneral);
							$oInfoPuntos->setUrlInstagram($urlInstagram);
							$oInfoPuntos->setUrlFacebook($urlFacebook);
							$oInfoPuntos->setUrlYoutube($urlYoutube);
							$oInfoPuntos->setUrlWeb($urlWeb);
							$oInfoPuntos->setIdPunto($oPuntos);

							$em->persist($oInfoPuntos);
							$em->flush();
						} 
						else{

							if($oInfoPuntos){
								$em->remove($oInfoPuntos);
								$em->flush();
							}
						}

						$mensaje = true;

						$errores = array(
							"id_tipo_punto"    => "",
							"titulo"           => "",
							"direccion"        => "",
							"left"             => "",
							"top"              => "",
							"slug"              => "",
							"imagen_principal" => "",
						);

						$valores = array(
							"id_tipo_punto"      =>  $oPuntos->getIdTipoPunto()->getId(),
							"titulo"             =>  $oPuntos->getTitulo(),
							"subtitulo"          =>  $oPuntos->getSubTitulo(),
							"direccion"          =>  $oPuntos->getDireccion(),
							"left"               =>  $oPuntos->getLeft(),
							"top"                =>  $oPuntos->getTop(),
							"slug"               =>  $oPuntos->getSlug(),
							"icono"              =>  $oPuntos->getIcono(),
							"infoGeneral"        =>  $infoGeneral,
							"urlInstagram"       =>  $urlInstagram,
							"urlFacebook"        =>  $urlFacebook,
							"urlYoutube"         =>  $urlYoutube,
							"urlWeb"             =>  $urlWeb,
							"id_punto"           =>  $id_punto
						);

						return $this->render('AdminBundle:Puntos:editar.html.twig', 
							array(
								'errores'     => $errores,
								'valores'     => $valores,
								'oTiposPunto' => $oTiposPunto,
								'mensaje'     => $mensaje
							)
						); 



					} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

						$arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
						return new JsonResponse($arrRespuesta);
					}

				}

				$errores = array(
					"id_tipo_punto"    => $id_tipo_punto ? "":"El tipo de punto es obligatorio",
					"titulo"           => $titulo ? "":"El titulo es obligatorio",
					"direccion"        => $direccion ? "":"La dirección es obligatoria",
					"letf"             => $letf ? "":"El letf es obligatorio",
					"top"              => $top ? "":"El top es obligatorio",
					"slug"              => $slug ? "":"El slug es obligatorio",
					"imagen_principal" => "",
				);

				$valores = array(
					"id_tipo_punto"      => $id_tipo_punto,
					"titulo"             => $titulo,
					"subtitulo"          => $subtitulo,
					"direccion"          => $direccion,
					"letf"               => $letf,
					"top"                => $top,
					"slug"               => $slug,
					"icono"              => $icono,
					"infoGeneral"        => $infoGeneral,
					"urlInstagram"       => $urlInstagram,
					"urlFacebook"        => $urlFacebook,
					"urlYoutube"         => $urlYoutube,
					"urlWeb"             => $urlWeb,
					"id_punto"           => $id_punto
				);

				return $this->render('AdminBundle:Puntos:editar.html.twig', 
					array(
						'errores'     => $errores,
						'valores'     => $valores,
						'oTiposPunto' => $oTiposPunto,
						'mensaje'     => $mensaje
					)
				);
			}

			$oPuntos = $em->getRepository('AdminBundle:Puntos')->find($id_punto);

			$oInfoPuntos = $em->getRepository('AdminBundle:InfoPuntos')->findOneBy(array('idPunto' => $id_punto));

			$errores = array(
				"id_tipo_punto"    => "",
				"titulo"           => "",
				"direccion"        => "",
				"left"             => "",
				"top"              => "",
				"slug"              => "",
				"imagen_principal" => "",
			);

			$valores = array(
				"id_tipo_punto"      =>  $oPuntos->getIdTipoPunto()->getId(),
				"titulo"             =>  $oPuntos->getTitulo(),
				"subtitulo"          =>  $oPuntos->getSubTitulo(),
				"direccion"          =>  $oPuntos->getDireccion(),
				"left"               =>  $oPuntos->getLeft(),
				"top"                =>  $oPuntos->getTop(),
				"slug"                => $oPuntos->getSlug(),
				"icono"              =>  $oPuntos->getIcono(),
				"infoGeneral"        =>  $oInfoPuntos ? $oInfoPuntos->getInfoGeneral(): "",
				"urlInstagram"       =>  $oInfoPuntos ? $oInfoPuntos->getUrlInstagram(): "",
				"urlFacebook"        =>  $oInfoPuntos ? $oInfoPuntos->getUrlFacebook(): "",
				"urlYoutube"         =>  $oInfoPuntos ? $oInfoPuntos->getUrlYoutube(): "",
				"urlWeb"             =>  $oInfoPuntos ? $oInfoPuntos->getUrlWeb(): "",
				"id_punto"           =>  $id_punto
			);

			return $this->render('AdminBundle:Puntos:editar.html.twig', 
				array(
					'errores'     => $errores,
					'valores'     => $valores,
					'oTiposPunto' => $oTiposPunto,
					'mensaje'     => $mensaje
				)
			);  
		}

		 /**
		 * @Route("/admin/puntos/eliminar", options={"expose"=true}, name="admin_puntos_eliminar")
		 */
		 public function eliminarAction(Request $request){

		 	$em = $this->getDoctrine()->getManager();

		 	date_default_timezone_set("America/Santiago");

		 	$dateHoy  = date("Y-m-d H:i:s");
		 	$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

		 	$id_punto = $request->request->get('id_punto');
		 	$oPuntos  = $em->getRepository('AdminBundle:Puntos')->find($id_punto);

		 	$oPuntos->setIdEstado($em->getReference('AdminBundle:Estados', 3));
		 	$oPuntos->setUpdatedAt($oDateHoy);

		 	$em->persist($oPuntos);
		 	$em->flush();

		 	$arrRespuesta = array("estado" => "success", "mensaje" => "El punto se ha eliminado correctamente.");
		 	return new JsonResponse($arrRespuesta);
		 }

		/**
		 * @Route("/admin/puntos/bloquear", options={"expose"=true}, name="admin_puntos_bloquear")
		*/
		public function bloquearAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$id_punto = $request->request->get('id_punto');
			$oPuntos  = $em->getRepository('AdminBundle:Puntos')->find($id_punto);

			$oPuntos->setIdEstado($em->getReference('AdminBundle:Estados', 2));
			$oPuntos->setUpdatedAt($oDateHoy);

			$em->persist($oPuntos);
			$em->flush();

			$arrRespuesta = array("estado" => "success", "mensaje" => "El Puntos se ha bloquear correctamente.");
			return new JsonResponse($arrRespuesta);
		}

		/**
		 * @Route("/admin/puntos/activar", options={"expose"=true}, name="admin_puntos_activar")
		*/
		public function activarAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

			$id_punto   = $request->request->get('id_punto');
			$oPuntos    = $em->getRepository('AdminBundle:Puntos')->find($id_punto);

			$oPuntos->setIdEstado($em->getReference('AdminBundle:Estados', 1));
			$oPuntos->setUpdatedAt($oDateHoy);

			$em->persist($oPuntos);
			$em->flush();

			$arrRespuesta = array("estado" => "success", "mensaje" => "El punto se ha activado correctamente.");
			return new JsonResponse($arrRespuesta);
		}

	 /**
	 * @Route("/admin/puntos/ver", options={"expose"=true}, name="admin_puntos_ver")
	 */
	 public function verAction(Request $request){

	 	$oInfoPuntos = array();

	 	$em = $this->getDoctrine()->getManager();

	 	$id_punto    = $request->request->get('id_punto');
	 	$oPuntos     = $em->getRepository('AdminBundle:Puntos')->find($id_punto);

	 	if($oPuntos->getIdTipoPunto()->getId() == 1){
	 		$oInfoPuntos = $em->getRepository('AdminBundle:InfoPuntos')->findOneBy(array('idPunto' => $id_punto));
	 	}

	 	return $this->render('AdminBundle:Puntos:ver.html.twig', 
	 		array(
	 			'oInfoPuntos' => $oInfoPuntos
	 		)
	 	);
	 }
	}   
