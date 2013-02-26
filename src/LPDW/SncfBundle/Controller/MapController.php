<?php

namespace LPDW\SncfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Ivory\GoogleMapBundle\IvoryGoogleMapBundle;

class MapController extends Controller
{
    /**
     * * @Route("/map")
     * @Template("LPDWGoogleMapBundle:Map:index.html.twig")
     */
    public function indexAction()
    {
        // appel du service google_map
        $map = $this->get('ivory_google_map.map');
        return array('map' =>$map);
    }
}
