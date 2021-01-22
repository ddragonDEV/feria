<?php

namespace FeriaUC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use FeriaUC\AdminBundle\Entity\ContenidoMultimedia;
use FeriaUC\AdminBundle\Entity\RelPuntoMultimedia;
use FeriaUC\AdminBundle\Entity\RelEstandMultimedia;

class ContenidoMultimediaController extends Controller
{
		/**
		 * @Route("/admin/contenido/multimedias/punto/{id_punto}", options={"expose"=true}, name="admin_contenido_multimedias_punto")
		*/
		public function indexPuntoAction($id_punto= null){

			if(!$id_punto){
               return $this->redirectToRoute('admin_puntos');
			}

			$em = $this->getDoctrine()->getManager();

			$oPuntos  = $em->getRepository('AdminBundle:Puntos')->find($id_punto);

			$oRelPuntoMultimedia = $em->getRepository('AdminBundle:RelPuntoMultimedia')->obtenerMultimediasPuntos($id_punto);

			$url_img = 'uploads/puntos/multimedias/'.$id_punto.'/';

			return $this->render('AdminBundle:ContenidoMultimedia:index.punto.html.twig', 
	        	array(
	        		'oPuntos'                  => $oPuntos,
	        		'oRelPuntoMultimedia'      => $oRelPuntoMultimedia,
	        		'id_punto'                 => $id_punto,
	        		'url_img'                  => $url_img,
	        	)
	        );	
		}

		/**
		 * @Route("/admin/contenido/multimedias/stand/{id_stand}", options={"expose"=true}, name="admin_contenido_multimedias_stand")
		*/
		public function indexStandAction($id_stand= null){

			if(!$id_stand){
               return $this->redirectToRoute('admin_puntos');
			}

			$em = $this->getDoctrine()->getManager();

			$oSatnds = $em->getRepository('AdminBundle:Stands')->find($id_stand);
	
			$oRelEstandMultimedia = $em->getRepository('AdminBundle:RelEstandMultimedia')->obtenerMultimediasStands($id_stand);

			$url_img = 'uploads/stands/multimedias/'.$id_stand.'/';

			return $this->render('AdminBundle:ContenidoMultimedia:index.stand.html.twig', 
	        	array(
					'oSatnds'                  => $oSatnds,
					'oRelEstandMultimedia'     => $oRelEstandMultimedia,
					'id_stand'                 => $id_stand,
					'id_punto'                 => $oSatnds->getIdPunto()->getId(),
					'url_img'                  => $url_img
	        	)
	        );	
		}

		/**
		 * @Route("/admin/contenido/multimedias/agregar", options={"expose"=true}, name="admin_contenido_multimedias_agregar")
		*/
		public function agregarAction(Request $request){

			$arr_id_contenido = array();

			$errores = array(
				"nombre"               => "",
				"id_tipo_multimedia"   => ""
			);

			$valores = array(
				"nombre"               => "",
				"id_tipo_multimedia"   => 1,
			);

			$em = $this->getDoctrine()->getManager();

			$oTiposMultimedia = $em->getRepository('AdminBundle:TiposMultimedia')->findAll();

			if ($request->getMethod() === 'POST') {

				date_default_timezone_set("America/Santiago");

				$dateHoy  = date("Y-m-d H:i:s");
                $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

                $id_punto           = $request->request->get('id_punto');
                $id_stand           = $request->request->get('id_stand');
                $id_tipo_multimedia = $request->request->get('id_tipo_multimedia');
				$nombre             = $request->request->get('nombre');

				$urls_youtube       = $request->files->get('urls_youtube');
				$codigos_youtube    = $request->request->get('codigos_youtube');
				$url_img            = $request->files->get('urls_img');

				if($id_tipo_multimedia == 2){
                  
	                 $multimedias_youtube = array();

					foreach ($urls_youtube as $key => $item) {
	                    $multimedias_youtube [] = array('url' => $item['url'], 'codigo_youtube' => $codigos_youtube[$key]['codigo_youtube']);
					}
				}

				if($nombre && $id_tipo_multimedia){

					if($id_punto){
								$ruta = $this->container->getparameter('kernel.root_dir').'/../web/uploads/puntos/multimedias/'.$id_punto;
							} else {
                               $ruta = $this->container->getparameter('kernel.root_dir').'/../web/uploads/stands/multimedias/'.$id_stand;
							}

							$count = 1;

							if($id_tipo_multimedia == 1){

								foreach ($url_img  as $item) {
								  
									   $nombre_count = $nombre.'-'.$count;	

									   $oContenidoMultimedia = new ContenidoMultimedia();

			                           $oContenidoMultimedia->setNombre($nombre_count);
			                           $oContenidoMultimedia->setCodigoYoutube(null);
								       $oContenidoMultimedia->setIdEstadoMultimedia($em->getReference('AdminBundle:EstadosMultimedia', 1));

								       $oContenidoMultimedia->setIdTipoMultimedia($em->getReference('AdminBundle:tiposMultimedia', 
								       	$id_tipo_multimedia));

								      $oContenidoMultimedia->setCreatedAt($oDateHoy);

								      $em->persist($oContenidoMultimedia);
									  $em->flush();

									  $count ++;

									  if (!file_exists($ruta)) {
										  mkdir($ruta, 0777, true);
									  }

									  $nombreOriginal = $item->getClientOriginalName();
									  $temporal       = $item->getPathName();
									  $extension      = $item->guessExtension();

									  $destino        = $ruta."/multimedia_".$oContenidoMultimedia->getId().".".$extension;

									  move_uploaded_file($temporal, $destino);

									  $oContenidoMultimedia->setUrl("multimedia_".$oContenidoMultimedia->getId().".".$extension);
									  $oContenidoMultimedia->setExtension($extension);

									  $em->persist($oContenidoMultimedia);
									  $em->flush();

									  $arr_id_contenido [] = $oContenidoMultimedia->getId();
								    }

							}

							else if($id_tipo_multimedia == 2){

									foreach ($multimedias_youtube  as $item) {
								  
									   $nombre_count = $nombre.'-'.$count;	

									   $oContenidoMultimedia = new ContenidoMultimedia();

			                           $oContenidoMultimedia->setNombre($nombre_count);
			                           $oContenidoMultimedia->setCodigoYoutube($item['codigo_youtube']);
								       $oContenidoMultimedia->setIdEstadoMultimedia($em->getReference('AdminBundle:EstadosMultimedia', 1));

								       $oContenidoMultimedia->setIdTipoMultimedia($em->getReference('AdminBundle:tiposMultimedia', 
								       	$id_tipo_multimedia));

								      $oContenidoMultimedia->setCreatedAt($oDateHoy);

								      $em->persist($oContenidoMultimedia);
									  $em->flush();

									  $count ++;

									  if (!file_exists($ruta)) {
										  mkdir($ruta, 0777, true);
									  }

									  $nombreOriginal = $item['url']->getClientOriginalName();
									  $temporal       = $item['url']->getPathName();
									  $extension      = $item['url']->guessExtension();

									  $destino        = $ruta."/multimedia_".$oContenidoMultimedia->getId().".".$extension;

									  move_uploaded_file($temporal, $destino);

									  $oContenidoMultimedia->setUrl("multimedia_".$oContenidoMultimedia->getId().".".$extension);
									  $oContenidoMultimedia->setExtension($extension);

									  $em->persist($oContenidoMultimedia);
									  $em->flush();

									  $arr_id_contenido [] = $oContenidoMultimedia->getId();
								    }
							}
					try {

							if($id_punto){
								for ($i=0; $i < count($arr_id_contenido); $i++) { 

									 $oRelPuntoMultimedia = new RelPuntoMultimedia();

									 $oRelPuntoMultimedia->setIdPunto($em->getReference('AdminBundle:Puntos', $id_punto));

									 $oRelPuntoMultimedia->setIdContenidoMultimedia($em->getReference('AdminBundle:ContenidoMultimedia', $arr_id_contenido[$i]));

									  $em->persist($oRelPuntoMultimedia);
									  $em->flush();
								}
							} else{

								for ($i=0; $i < count($arr_id_contenido); $i++) { 
									 
									  $oRelEstandMultimedia = new RelEstandMultimedia();

									  $oRelEstandMultimedia->setIdStand($em->getReference('AdminBundle:Stands', $id_stand));

									  $oRelEstandMultimedia->setIdContenidoMultimedia($em->getReference('AdminBundle:ContenidoMultimedia', $arr_id_contenido[$i]));

									  $em->persist($oRelEstandMultimedia);
									  $em->flush();
								}
							}

							$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
							return new JsonResponse($arrRespuesta);

						} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

							 $arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
							 return new JsonResponse($arrRespuesta);
						}
				}

				$errores = array(
				"nombre"               => $nombre ? "":"El nombre es obligatorio",
				"id_tipo_multimedia"   => $id_tipo_multimedia ? "":"El tipo de multimedia es obligatorio"
				);

				$valores = array(
					"nombre"               => $nombre,
					"id_tipo_multimedia"   => $id_tipo_multimedia,
				);

				return $this->render('AdminBundle:ContenidoMultimedia:agregar.html.twig', 
		        	array(
		        		'errores'          => $errores,
		        		'valores'          => $valores,
		        		'oTiposMultimedia' => $oTiposMultimedia,
		        		'id_punto'         => $id_punto,
		        		'id_stand'         => $id_stand
		        	)
		        ); 
			}

			$id_punto = $request->query->get('id_punto');
			$id_stand  = $request->query->get('id_stand');
			
			return $this->render('AdminBundle:ContenidoMultimedia:agregar.html.twig', 
	        	array(
	        		'errores'          => $errores,
	        		'valores'          => $valores,
	        		'oTiposMultimedia' => $oTiposMultimedia,
	        		'id_punto'         => $id_punto,
	        		'id_stand'         => $id_stand
	        	)
	        ); 	
		}

		/**
		 * @Route("/admin/contenido/multimedias/editar", options={"expose"=true}, name="admin_contenido_multimedias_editar")
		*/
		public function editarAction(Request $request){

			 $multimedias_youtube = array();

			$em = $this->getDoctrine()->getManager();

			$oTiposMultimedia = $em->getRepository('AdminBundle:TiposMultimedia')->findAll();

			if ($request->getMethod() === 'POST') {

				date_default_timezone_set("America/Santiago");

				$dateHoy  = date("Y-m-d H:i:s");
                $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

                $id_contenido_multimedia = $request->request->get('id_contenido_multimedia');
                $oContenidoMultimedia = $em->getRepository('AdminBundle:ContenidoMultimedia')->find($id_contenido_multimedia);


				$id_punto           = $request->request->get('id_punto');
                $id_stand           = $request->request->get('id_stand');

				$nombre             = $request->request->get('nombre');

				$id_punto           = $request->request->get('id_punto');
                $id_stand           = $request->request->get('id_stand');
                $totalMultimedia    = $request->request->get('totalMultimedia');
                $id_tipo_multimedia = $request->request->get('id_tipo_multimedia');

				$url_youtube        = $request->files->get('url_youtube');
				$codigo_youtube     = $request->request->get('codigo_youtube');
				$url_img            = $request->files->get('url_img');

				if($id_punto){
					$ruta = $this->container->getparameter('kernel.root_dir').'/../web/uploads/puntos/multimedias/'.$id_punto.'/';
				} else {
                   $ruta = $this->container->getparameter('kernel.root_dir').'/../web/uploads/stands/multimedias/'.$id_stand.'/';
				}

				if($nombre && $id_tipo_multimedia){

					$oContenidoMultimedia->setNombre($nombre);

					$oContenidoMultimedia->setIdTipoMultimedia($em->getReference('AdminBundle:tiposMultimedia', 
                    $id_tipo_multimedia));

			
					$oContenidoMultimedia->setUpdatedAt($oDateHoy);

					try {
							$em->persist($oContenidoMultimedia);
							$em->flush();

							if($id_tipo_multimedia == 1){

								$oContenidoMultimedia->setCodigoYoutube(null);

								if($url_img){

									if(is_file($ruta.$oContenidoMultimedia->getUrl())){
										unlink($ruta.$oContenidoMultimedia->getUrl());
									}

									$nombreOriginal = $url_img->getClientOriginalName();
									$temporal       = $url_img->getPathName();
									$extension      = $url_img->guessExtension();

									$destino        = $ruta."multimedia_".$id_contenido_multimedia.".".$extension;

									move_uploaded_file($temporal, $destino);

									$oContenidoMultimedia->setUrl("multimedia_".$id_contenido_multimedia.".".$extension);
									$oContenidoMultimedia->setExtension($extension);
									
									$em->persist($oContenidoMultimedia);
									$em->flush();
							   }
							}

							else if($id_tipo_multimedia == 2){

									 if($url_youtube && $codigo_youtube){

									   if(is_file($ruta.$oContenidoMultimedia->getUrl())){
										  unlink($ruta.$oContenidoMultimedia->getUrl());
									    }
									  
									   $nombreOriginal = $url_youtube->getClientOriginalName();
									   $temporal       = $url_youtube->getPathName();
									   $extension      = $url_youtube->guessExtension();

									   $destino        = $ruta."/multimedia_".$oContenidoMultimedia->getId().".".$extension;

									   move_uploaded_file($temporal, $destino);

									   $oContenidoMultimedia->setUrl("multimedia_".$oContenidoMultimedia->getId().".".$extension);
									   $oContenidoMultimedia->setExtension($extension);
									   $oContenidoMultimedia->setCodigoYoutube($codigo_youtube);

									   $em->persist($oContenidoMultimedia);
									   $em->flush();   
								}
								else{

									$errores = array(
										"nombre"               => $nombre ? "":"El nombre es obligatorio",
										"id_tipo_multimedia"   => $id_tipo_multimedia ? "":"El tipo de multimedia es obligatorio",
										"url_youtube"          => $url_youtube ? "": "La imagen es obligatario",
										"codigo_youtube"       => $codigo_youtube ? "": "El codigo de youtube es obligatario"
									);

									$valores = array(
										"nombre"                     => $nombre,
										"id_tipo_multimedia"         => $id_tipo_multimedia,
										"codigo_youtube"             => $codigo_youtube,
										"id_punto"                   => $id_punto,
										"id_stand"                   => $id_stand,
										'id_contenido_multimedia'    => $id_contenido_multimedia
									);

									return $this->render('AdminBundle:ContenidoMultimedia:editar.html.twig', 
							        	array(
							        		'errores'                    => $errores,
							        		'valores'                    => $valores,
							        		'oTiposMultimedia'           => $oTiposMultimedia,
							        	)
							        );
								}
							}

							$arrRespuesta = array("estado" => "OK", "mensaje" => "Los datos se han guardado correctamente");
							return new JsonResponse($arrRespuesta);

						} catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

							 $arrRespuesta = array("estado" => "ERROR", "mensaje" => "Se ha producido un error al intentar guardar los datos");
							 return new JsonResponse($arrRespuesta);
						}
				}

				$errores = array(
					"nombre"               => $nombre ? "":"El nombre es obligatorio",
					"id_tipo_multimedia"   => $id_tipo_multimedia ? "":"El tipo de multimedia es obligatorio",
					"url_youtube"          =>"",
					"codigo_youtube"       =>""
				);

				$valores = array(
					"nombre"                     => $nombre,
					"id_tipo_multimedia"         => $id_tipo_multimedia,
					"codigo_youtube"             => $oContenidoMultimedia->getCodigoYoutube(),
					"id_punto"                   => $id_punto,
					"id_stand"                   => $id_stand,
					'id_contenido_multimedia'    => $id_contenido_multimedia
				);

				return $this->render('AdminBundle:ContenidoMultimedia:editar.html.twig', 
		        	array(
		        		'errores'                    => $errores,
		        		'valores'                    => $valores,
		        		'oTiposMultimedia'           => $oTiposMultimedia,
		        	)
		        );
			}

			$id_contenido_multimedia = $request->query->get('id_contenido_multimedia');
			$oContenidoMultimedia = $em->getRepository('AdminBundle:ContenidoMultimedia')->find($id_contenido_multimedia);

			$id_punto          = $request->query->get('id_punto');
            $id_stand          = $request->query->get('id_stand');

			$errores = array(
				"nombre"                   => "",
				"id_tipo_multimedia"       => "",
				"url_youtube"              => "",
				"codigo_youtube"           => ""
			);

			$codigo_youtube = '';

			if($oContenidoMultimedia->getCodigoYoutube()){
				$codigo_youtube = $oContenidoMultimedia->getCodigoYoutube();
			}

			$valores = array(
				"nombre"                  => $oContenidoMultimedia->getNombre(),
				"id_tipo_multimedia"      => $oContenidoMultimedia->getIdTipoMultimedia()->getId(),
				"codigo_youtube"          => $codigo_youtube,
				'id_contenido_multimedia' => $id_contenido_multimedia,
				"id_punto"                => $id_punto,
				"id_stand"                => $id_stand
			);

			return $this->render('AdminBundle:ContenidoMultimedia:editar.html.twig', 
	        	array(
	        		'errores'          => $errores,
	        		'valores'          => $valores,
	        		'oTiposMultimedia' => $oTiposMultimedia
	        	)
	        );
		}

		/**
		 * @Route("/admin/contenido/multimedias/eliminar", options={"expose"=true}, name="admin_contenido_multimedias_eliminar")
		*/
		public function eliminarAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
            $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

            $id_contenido_multimedia = $request->request->get('id_contenido_multimedia');
			$oContenidoMultimedia = $em->getRepository('AdminBundle:ContenidoMultimedia')->find($id_contenido_multimedia);

			$id_punto =  $request->request->get('id_punto');
			$id_stand =  $request->request->get('id_stand');

			if($id_punto){
               $ruta = $this->container->getparameter('kernel.root_dir').'/../web/uploads/puntos/multimedias/'.$id_punto.'/';

               $oRelPuntoMultimedia = $em->getRepository('AdminBundle:RelPuntoMultimedia')->findOneBy(array('idPunto'=> $id_punto,
               	'idContenidoMultimedia' => $id_contenido_multimedia));

               $em->remove($oRelPuntoMultimedia);
		       $em->flush(); 
			}
			else if($id_stand){
              $ruta = $this->container->getparameter('kernel.root_dir').'/../web/uploads/stands/multimedias/'.$id_stand.'/';

              $oRelEstandMultimedia = $em->getRepository('AdminBundle:RelEstandMultimedia')->findOneBy(array('idStand'=> $id_stand,
               	'idContenidoMultimedia' => $id_contenido_multimedia));

               $em->remove($oRelEstandMultimedia);
		       $em->flush();
			}

			if(is_file($ruta.$oContenidoMultimedia->getUrl())){
				unlink($ruta.$oContenidoMultimedia->getUrl());
			}

			$em->remove($oContenidoMultimedia);
		    $em->flush(); 

			// $oContenidoMultimedia->setIdEstadoMultimedia($em->getReference('AdminBundle:EstadosMultimedia', 3));
   //          $oContenidoMultimedia->setUpdatedAt($oDateHoy);
        
	        // $em->persist($oContenidoMultimedia);
	        // $em->flush();

	        $arrRespuesta = array("estado" => "success", "mensaje" => "La multimedia se ha eliminado correctamente.");
        	return new JsonResponse($arrRespuesta);
		}

		/**
		 * @Route("/admin/contenido/multimedias/bloquear", options={"expose"=true}, name="admin_contenido_multimedias_bloquear")
		*/
		public function bloquearAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
            $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

            $id_contenido_multimedia = $request->request->get('id_contenido_multimedia');
			$oContenidoMultimedia = $em->getRepository('AdminBundle:ContenidoMultimedia')->find($id_contenido_multimedia);

			$oContenidoMultimedia->setIdEstadoMultimedia($em->getReference('AdminBundle:EstadosMultimedia', 2));
            $oContenidoMultimedia->setUpdatedAt($oDateHoy);
        
	        $em->persist($oContenidoMultimedia);
	        $em->flush();

	        $arrRespuesta = array("estado" => "success", "mensaje" => "La multimedia se ha bloquedo correctamente.");
        	return new JsonResponse($arrRespuesta);
		}

		/**
		 * @Route("/admin/contenido/multimedias/activar", options={"expose"=true}, name="admin_contenido_multimedias_activar")
		*/
		public function activarAction(Request $request){

			$em = $this->getDoctrine()->getManager();

			date_default_timezone_set("America/Santiago");

			$dateHoy  = date("Y-m-d H:i:s");
            $oDateHoy = new \DateTime(date('Y-m-d H:i', strtotime($dateHoy)));

            $id_contenido_multimedia = $request->request->get('id_contenido_multimedia');
			$oContenidoMultimedia = $em->getRepository('AdminBundle:ContenidoMultimedia')->find($id_contenido_multimedia);

			$oContenidoMultimedia->setIdEstadoMultimedia($em->getReference('AdminBundle:EstadosMultimedia', 1));
            $oContenidoMultimedia->setUpdatedAt($oDateHoy);
        
	        $em->persist($oContenidoMultimedia);
	        $em->flush();

	        $arrRespuesta = array("estado" => "success", "mensaje" => "La multimedia se ha activo correctamente.");
        	return new JsonResponse($arrRespuesta);
		}
}   