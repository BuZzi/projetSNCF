<?php

namespace LPDW\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PositionController extends Controller
{
    /**
     * @Route("/accueil")
     * @Template("LPDWClientBundle:Default:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route ("/position", name="lpdw_position", options={"expose"=true})
     * @Template("LPDWClientBundle:Client:position.html.twig")
     */
    public function positionAction(Request $request)
    {
        $lat = $request->request->get('lat');
        $lng = $request->request->get('lng');
        return array('latitude' => $lat, 'longitude' =>$lng);
    }

}
