<?php

namespace LPDW\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template("LPDWClientBundle:Default:index.html.twig")
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route ("/position", name="lpdw_position", options={"expose"=true})
     * @Template("LPDWClientBundle:Client:index.html.twig")
     */
    public function positionAction(Request $request)
    {
        $lat = $request->request->get('lat');
        $lng = $request->request->get('lng');
        return array('latitude' => $lat, 'longitude' =>$lng);
    }

}
