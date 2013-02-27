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
        $_mLatitude = $request->request->get('lat');
        $_mLongitude = $request->request->get('lng');
        $_sFormule = "(6366*acos(cos(radians($_mLatitude))*cos(radians(`latitude`))*cos(radians(`longitude`) -radians($_mLongitude))+sin(radians($_mLatitude))*sin(radians(`latitude`))))";
        $_sSql = "SELECT ville, $_sFormule AS dist FROM ville WHERE $_sFormule <= '10' ORDER by dist ASC";

        $_ListStation = $this->getDoctrine()
            ->getRepository('LPDWSncfBundle:Station')
            ->findBy(
            array(),
            array('creationDate' => 'DESC')
        );

        return array('latitude' => $_mLatitude, 'longitude' => $_mLongitude);
    }

}
