<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use FeriaUC\AdminBundle\Entity\Stands;

class StandsController extends Controller
{
		/**
		 * @Route("/admin/stands/listado/{id_punto}", options={"expose"=true}, name="admin_stands")
		*/
		public function indexAction($id_punto=null){
		    $em = $this->getDoctrine()->getManager();	

			if(!$id_punto){
				return $this->redirectToRoute('admin_puntos');
			}
				 
			return $this->render('AdminBundle:Stands:index.html.twig', 
			   array(
					'id_punto' => $id_punto 
				 )
			);
		}

	  /**
     * @Route("/admin/stands/datatables", options={"expose"=true}, name="admin_stands_datatables")
    */
    public function datatablesAction(Request $request){

    	$em = $this->getDoctrine()->getManager();

		$start  = $request->query->get('start');
		$length = $request->query->get('length');
		$columns = $request->query->get('columns');
		$id_punto = $request->query->get('id_punto');
		
		$dataTable = $this->container->get('datatable_server_side')->serverProcessingStands($columns, $start, $length, $id_punto);

		return new JsonResponse($dataTable);
    }

		/**
		 * @Route("/admin/stands/agregar/{id_punto}", options={"expose"=true}, name="admin_stands_agregar")
		*/
		public function agregarAction(Request $request, $id_punto){
			$em = $this->getDoctrine()->getManager();

			$mensaje = false;

			$ruta_malla_curricular = $this->container->getparameter('kernel.root_dir').'/../web/uploads/stands/malla_curricular/';

			$ruta_imagen_principal = $this->container->getparameter('kernel.root_dir').'/../web/uploads/stands/imagen_principal/';

			$errores = array(
				"nombre"   => ""
			);

			$valores = array(
				"id_punto"         => $id_punto,
				"nombre"           => "",
				"infoGeneral"      => "",
				"urlInstagram"     => "",
				"urlFacebook"      => "",
				"urlYoutube"       => "",
				"id_categoria"     => "",
				"mallaCurricular"  => "",
				"imagen_principal" => "",
				"urlWeb"           => ""
			);

			$oCategorias = $em->getRepository('AdminBundle:Categorias')->findAll();

			if ($request->getMethod() === 'POST') {

			   	date_default_timezone_set("America/Santiago");

				$dateHoy  = date("Y-m-d H:i:s");
				$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

				$nombre               = $request->request->get('nombre');
				$infoGeneral          = $request->request->get('infoGeneral');
				$urlInstagram         = $request->request->get('urlInstagram');
				$urlFacebook          = $request->request->get('urlFacebook');
				$urlYoutube           = $request->request->get('urlYoutube');
				$urlWeb               = $request->request->get('urlWeb');
				$id_categoria         = $request->request->get('id_categoria');
				$id_punto             = $request->request->get('id_punto');

				$mallaCurricular  = $request->files->get('mallaCurricular');
				$imagen_principal = $request->files->get('imagen_principal');

				if($nombre &&  $id_punto){

					$oStands = new Stands();

					$oStands->setNombre($nombre);
					$oStands->setInfoGeneral($infoGeneral);
					$oStands->setUrlInstagram($urlInstagram);
					$oStands->setUrlFacebook($urlFacebook);
					$oStands->setUrlYoutube($urlYoutube);
					$oStands->setUrlWeb($urlWeb);
                    
                    if($id_categoria){
                    	$oStands->setIdCategoria($em->getReference('AdminBundle:Categorias', $id_categoria));
                    }

					$oStands->setIdPunto($em->getReference('AdminBundle:Puntos', $id_punto));
					$oStands->setIdEstado($em->getReference('AdminBundle:Estados', 1));
					$oStands->setCreatedAt($oDateHoy);

					try {

						$em->persist($oStands);
						$em->flush();

						if($mallaCurricular){

							$nombreOriginal = $mallaCurricular->getClientOriginalName();
							$temporal       = $mallaCurricular->getPathName();
							$extension      = $mallaCurricular->guessExtension();

							$destino        = $ruta_malla_curricular."malla_curricular_".$oStands->getId().".".$extension;

							move_uploaded_file($temporal, $destino);

							$oStands->setMallaCurricular("malla_curricular_".$oStands->getId().".".$extension);

							$em->persist($oStands);
							$em->flush();
						}

						if($imagen_principal){

							$nombreOriginal = $imagen_principal->getClientOriginalName();
							$temporal       = $imagen_principal->getPathName();
							$extension      = $imagen_principal->guessExtension();

							$destino        = $ruta_imagen_principal."imagen_principal_".$oStands->getId().".".$extension;

							move_uploaded_file($temporal, $destino);

							$oStands->setImagenPrincipal("imagen_principal_".$oStands->getId().".".$extension);

							$em->persist($oStands);
							$em->flush();
						}

						$valores = array(
							"id_punto"         => $id_punto,
							"nombre"           => "",
							"infoGeneral"      => "",
							"urlInstagram"     => "",
							"urlFacebook"      => "",
							"urlYoutube"       => "",
							"id_categoria"     => "",
							"mallaCurricular"  => "",
							"imagen_principal" => "",
							"urlWeb"           => ""
						);

						
						$mensaje = true;
						return $this->render('AdminBundle:Stands:agregar.html.twig', 
							array(
								'errores'     => $errores,
								'valores'     => $valores,
								'oCategorias' => $oCategorias,
								'mensaje'     => $mensaje
							)
						);

					} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

						 $arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
						 return new JsonResponse($arrRespuesta);
					}
				}

			 	$errores = array(
					"nombre" => $nombre ? "":"El nombre es obligatorio",
				);

				$valores = array(
					"id_punto"         => $id_punto,
					"nombre"           => $nombre,
					"infoGeneral"      => $infoGeneral,
					"urlInstagram"     => $urlInstagram,
					"urlFacebook"      => $urlFacebook,
					"urlYoutube"       => $urlYoutube,
					"urlWeb"           => $urlWeb,
					"id_categoria"     => $id_categoria,
					"mallaCurricular"  => "",
					"imagen_principal" => ""
				);

				return $this->render('AdminBundle:Stands:agregar.html.twig', 
					array(
						'errores'     => $errores,
						'valores'     => $valores,
						'oCategorias' => $oCategorias,
						'id_punto'    => $id_punto,
						'mensaje'     => $mensaje
					)
				);
			}

			$errores = array(
				"nombre" => ""
			);

			$valores = array(
				"id_punto"           => $id_punto,
				"nombre"             => "",
				"infoGeneral"        => "",
				"urlInstagram"       => "",
				"urlFacebook"        => "",
				"urlYoutube"         => "",
				"id_categoria"       => "",
				"mallaCurricular"    => "",
				"imagen_principal"   => "",
				"urlWeb"             => ""
			);

			return $this->render('AdminBundle:Stands:agregar.html.twig', 
				array(
				'errores'     => $errores,
				'valores'     => $valores,
				'oCategorias' => $oCategorias,
				'mensaje'     => $mensaje
				)
			);  
		}

		/**
		 * @Route("/admin/stands/editar/{id_stand}", options={"expose"=true}, name="admin_stands_editar")
		*/
		public function editarAction(Request $request, $id_stand=null){

			$mensaje = false;

			$ruta_malla_curricular = $this->container->getparameter('kernel.root_dir').'/../web/uploads/stands/malla_curricular/';

			$ruta_imagen_principal = $this->container->getparameter('kernel.root_dir').'/../web/uploads/stands/imagen_principal/';

			$em = $this->getDoctrine()->getManager();

			$oCategorias = $em->getRepository('AdminBundle:Categorias')->findAll();

			$oPuntos = $em->getRepository('AdminBundle:Puntos')->findBy(array('idEstado' =>1));

			if ($request->getMethod() === 'POST') {

				    date_default_timezone_set("America/Santiago");

					$dateHoy  = date("Y-m-d H:i:s");
					$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

					$id_stand = $request->request->get('id_stand');
					$oStands  = $em->getRepository('AdminBundle:Stands')->find($id_stand);

					$nombre               = $request->request->get('nombre');
					$infoGeneral          = $request->request->get('infoGeneral');
					$urlInstagram         = $request->request->get('urlInstagram');
					$urlFacebook          = $request->request->get('urlFacebook');
					$urlYoutube           = $request->request->get('urlYoutube');
					$urlWeb               = $request->request->get('urlWeb');
					$id_categoria         = $request->request->get('id_categoria');
					$id_punto             = $request->request->get('id_punto');

					$mallaCurricular  = $request->files->get('mallaCurricular');
					$imagen_principal = $request->files->get('imagen_principal');

					if($nombre && $id_punto){

						$oStands->setNombre($nombre);
						$oStands->setInfoGeneral($infoGeneral);
						$oStands->setUrlInstagram($urlInstagram);
						$oStands->setUrlFacebook($urlFacebook);
						$oStands->setUrlYoutube($urlYoutube);
						$oStands->setUrlWeb($urlWeb);

						$oStands->setIdCategoria(null);

						if($id_categoria){
						  $oStands->setIdCategoria($em->getReference('AdminBundle:Categorias', $id_categoria));
						}
						
						$oStands->setIdPunto($em->getReference('AdminBundle:Puntos', $id_punto));
						$oStands->setUpdatedAt($oDateHoy);

						try {

							$em->persist($oStands);
							$em->flush();

							if($mallaCurricular){

								if(is_file($ruta_malla_curricular.$oStands->getMallaCurricular())){
								   unlink($ruta_malla_curricular.$oStands->getMallaCurricular());
								}

								$nombreOriginal = $mallaCurricular->getClientOriginalName();
								$temporal       = $mallaCurricular->getPathName();
								$extension      = $mallaCurricular->guessExtension();

								$destino        = $ruta_malla_curricular."malla_curricular_".$oStands->getId().".".$extension;

								move_uploaded_file($temporal, $destino);

								$oStands->setMallaCurricular("malla_curricular_".$oStands->getId().".".$extension);

								$em->persist($oStands);
								$em->flush();
							}

							if($imagen_principal){

								if(is_file($ruta_imagen_principal.$oStands->getImagenPrincipal())){
								   unlink($ruta_imagen_principal.$oStands->getImagenPrincipal());
								}

								$nombreOriginal = $imagen_principal->getClientOriginalName();
								$temporal       = $imagen_principal->getPathName();
								$extension      = $imagen_principal->guessExtension();

								$destino        = $ruta_imagen_principal."imagen_principal_".$oStands->getId().".".$extension;

								move_uploaded_file($temporal, $destino);

								$oStands->setImagenPrincipal("imagen_principal_".$oStands->getId().".".$extension);

								$em->persist($oStands);
								$em->flush();
						   }

							$errores = array(
								"nombre"                 => "",
								"id_punto"               => ""
							);

							$valores = array(
								"nombre"                 => $oStands->getNombre(),
								"infoGeneral"            => $oStands->getInfoGeneral(),
								"urlInstagram"           => $oStands->getUrlInstagram(),
								"urlFacebook"            => $oStands->getUrlFacebook(),
								"urlYoutube"             => $oStands->getUrlYoutube(),
								"id_categoria"           => $oStands->getIdCategoria() ? $oStands->getIdCategoria()->getId(): "",
								"id_punto"               => $oStands->getIdPunto()->getId(),
								"mallaCurricular"        => $oStands->getMallaCurricular(),
								"imagen_principal"       => $oStands->getImagenPrincipal(),
								"urlWeb"                 => $oStands->getUrlWeb(),
								"id_stand"               => $id_stand
							);

							$mensaje = true;

							return $this->render('AdminBundle:Stands:editar.html.twig', 
								 array(
									'errores'           => $errores,
									'valores'           => $valores,
									'oCategorias'       => $oCategorias,
									'oPuntos'           => $oPuntos,
									'mensaje'           => $mensaje
								 )
							 );

						} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

							 $arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
							 return new JsonResponse($arrRespuesta);
						}
					}

					$errores = array(
					"nombre"                 => $nombre ? "":"El nombre es obligatorio",
					"id_punto"               => $id_punto ? "":"El ounto es obligatorio"
					);

					$valores = array(
						"nombre"                 => $nombre,
						"infoGeneral"            => $infoGeneral,
						"urlInstagram"           => $urlInstagram,
						"urlFacebook"            => $urlFacebook,
						"urlYoutube"             => $urlYoutube,
						"id_categoria"           => $id_categoria,
						"id_punto"               => $id_punto,
						"mallaCurricular"        => $oStands->getMallaCurricular(),
						"imagen_principal"       => $oStands->getImagenPrincipal(),
						"urlWeb"                 => $urlWeb,
						"id_stand"               => $id_stand
					);

					return $this->render('AdminBundle:Stands:editar.html.twig', 
					 array(
						'errores'           => $errores,
						'valores'           => $valores,
						'oCategorias'       => $oCategorias,
						'oPuntos'           => $Puntos,
						'mensaje'           => $mensaje
					 )
				 );
			}

			$oStands = $em->getRepository('AdminBundle:Stands')->find($id_stand);

			$errores = array(
				"nombre"                 => "",
				"id_punto"               => ""
			);

			$valores = array(
				"nombre"                 => $oStands->getNombre(),
				"infoGeneral"            => $oStands->getInfoGeneral(),
				"urlInstagram"           => $oStands->getUrlInstagram(),
				"urlFacebook"            => $oStands->getUrlFacebook(),
				"urlYoutube"             => $oStands->getUrlYoutube(),
				"urlWeb"                 => $oStands->getUrlWeb(),
				"id_categoria"           => $oStands->getIdCategoria() ? $oStands->getIdCategoria()->getId(): "",
				"id_punto"               => $oStands->getIdPunto()->getId(),
				"mallaCurricular"        => $oStands->getMallaCurricular(),
				"imagen_principal"       => $oStands->getImagenPrincipal(),
				"id_stand"               => $id_stand
			);

			return $this->render('AdminBundle:Stands:editar.html.twig', 
				 array(
					'errores'           => $errores,
					'valores'           => $valores,
					'oCategorias'       => $oCategorias,
					'oPuntos'           => $oPuntos,
					'mensaje'           => $mensaje
				 )
			 );  
		}

		 /**
		 * @Route("/admin/stands/eliminar", options={"expose"=true}, name="admin_stands_eliminar")
		 */
		public function eliminarAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
			
			$id_stand   = $request->request->get('id_stand');
			$oStands    = $em->getRepository('AdminBundle:Stands')->find($id_stand);
			
			$oStands->setIdEstado($em->getReference('AdminBundle:Estados', 3));
			$oStands->setUpdatedAt($oDateHoy);
			
			$em->persist($oStands);
			$em->flush();

			$arrRespuesta = array("estado" => "success", "mensaje" => "El stand se ha eliminado correctamente.");
			return new JsonResponse($arrRespuesta);
		}

		/**
		 * @Route("/admin/stands/bloquear", options={"expose"=true}, name="admin_stands_bloquear")
		*/
		public function bloquearAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
			
			$id_stand = $request->request->get('id_stand');
			$oStands  = $em->getRepository('AdminBundle:Stands')->find($id_stand);

			$oStands->setIdEstado($em->getReference('AdminBundle:Estados', 2));
			$oStands->setUpdatedAt($oDateHoy);
			
			$em->persist($oStands);
			$em->flush();

			$arrRespuesta = array("estado" => "success", "mensaje" => "El stand se ha bloquear correctamente.");
			return new JsonResponse($arrRespuesta);
		}

		/**
		 * @Route("/admin/stands/activar", options={"expose"=true}, name="admin_stands_activar")
		*/
		public function activarAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
			$oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));
			
			$id_stand   = $request->request->get('id_stand');
			$oStands    = $em->getRepository('AdminBundle:Stands')->find($id_stand);

			$oStands->setIdEstado($em->getReference('AdminBundle:Estados', 1));
			$oStands->setUpdatedAt($oDateHoy);
			
			$em->persist($oStands);
			$em->flush();

			$arrRespuesta = array("estado" => "success", "mensaje" => "El stand se ha activado correctamente.");
			return new JsonResponse($arrRespuesta);
		}

	 /**
	 * @Route("/admin/stand/ver", options={"expose"=true}, name="admin_stands_ver")
	 */
	 public function verAction(Request $request){

		$em = $this->getDoctrine()->getManager();

		$id_stand   = $request->request->get('id_stand');
		$oStands    = $em->getRepository('AdminBundle:Stands')->find($id_stand);

		return $this->render('AdminBundle:Stands:ver.html.twig', 
			 array(
				'oStands' => $oStands
			 )
		);
	 }
 }   