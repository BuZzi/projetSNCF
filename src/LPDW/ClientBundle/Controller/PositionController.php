<?php

namespace LPDW\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use LPDW\SncfBundle\Entity\Station,
    LPDW\ClientBundle\Form\SearchStationType;

class PositionController extends Controller
{
    /**
     * @Route("/accueil")
     * @Template("LPDWClientBundle:Default:index.html.twig")
     */
    public function indexAction(Request $_oRequest)
    {
        $_oStation = new Station();
        $_oForm = $this->createForm(new SearchStationType(), $_oStation);

        if($_oRequest->isMethod('POST'))
        {
            $_oForm->bind($_oRequest);

            if($_oForm->isValid())
            {
                $_oStation = $_oForm->getData();

                $em = $this->getDoctrine()->getManager();

                return array();
            }
        }

        // Render the form in the view
        return array(
            'form' => $_oForm->createView(),
        );
    }

    /**
     * Fonction ajax
     *
     * @Route ("/position", name="lpdw_position", options={"expose"=true})
     * @Template("LPDWClientBundle:Client:position.html.twig")
     */
    public function positionAction(Request $request)
    {
        $_mLatitude = $request->request->get('lat');
        $_mLongitude = $request->request->get('lng');

        $_aListStations = $this->get('lpdw_sncf.station_manager')->getAroundStation($_mLatitude, $_mLongitude);



        if (!$_aListStations) {
            throw $this->createNotFoundException('Pas de gares à l\'horizon');
        }


        foreach($_aListStations as $_iKey=> &$_aStation){
            $_aStation = $this->getDoctrine()
                ->getRepository('LPDWSncfBundle:Station')
                ->find($_aStation['id']);
        }

        var_dump($_aListStations);

        return array(
                'latitude' => $_mLatitude,
                'longitude' => $_mLongitude,
                'listStations' => $_aListStations
        );
    }

}
