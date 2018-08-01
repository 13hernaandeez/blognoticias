<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/inicio", name="inicio")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Noticia');

        $noticia = $repository->findAllOrderedByFecha();

        
        return $this->render('default/inicio.html.twig', 
            array(
                'noticia'=>$noticia)
        );
    }


    /**
     * @Route("/noticia/{id}", name="noticia", requirements={"id"="\d+"})
     */
    public function tareaAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Noticia');

        $noticia = $repository->findOneById($id);

        $url_atras = $this->generateUrl('homepage');

        return $this->render('default/noticia_unica.html.twig', 
            array(
                'noticia'=>$noticia)
        );
    }


    /**
       * @Route("/noticia.{_format}", name="noticia_json_xml", requirements={"_format": "json|xml"})
     */
    public function tareasJsonAction($_format)
    {
        
        $repository = $this->getDoctrine()->getRepository('AppBundle:Noticia');
        $noticia = $repository->findAllOrderedByFecha();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContenido=$serializer->serialize($noticia, 'json');

        $response = new Response();
        $response->headers->set('Content-type', 'application/json');
        $response->setContent($jsonContenido);
        return $response;
    }
}