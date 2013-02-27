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
     * Rend le formulaire de recherche d'une gare dans la vue, traite les données s'il est soumis
     *
     * @Route("/accueil", name="lpdw_search")
     * @Template("LPDWClientBundle:Default:index.html.twig")
     */
    public function accueilAction(Request $_oRequest)
    {
        $_oStation = new Station();
        $_oForm = $this->createForm(new SearchStationType(), $_oStation);

        if($_oRequest->isMethod('POST'))
        {
            $_oForm->bind($_oRequest);

            if($_oForm->isValid())
            {
                $_sNameStation = $_oForm['name']->getData();


                // appel du service du manager de l'entité Station
                $_oStation = $this->get('lpdw_sncf.station_manager')->findStationByName($_sNameStation);

                return array(
                    'station' => $_oStation
                );
            }
        }

        // Render the form in the view
        return array(
            'form' => $_oForm->createView(),
        );
    }

    /**
     * Fonction appelé en ajax lorsque l'utilisateur accepte de partager ses coordonnées
     *
     * @Route ("/position", name="lpdw_position", options={"expose"=true})
     * @Template("LPDWClientBundle:Client:position.html.twig")
     */
    public function positionAction(Request $request)
    {
        $_mLatitude = $request->request->get('lat');
        $_mLongitude = $request->request->get('lng');

        // appel du service du manager de l'entité Station
        $_aListStations = $this->get('lpdw_sncf.station_manager')->getAroundStation($_mLatitude, $_mLongitude);


        if (!$_aListStations)
        {
            throw $this->createNotFoundException('Pas de gares à l\'horizon. Utiliser plutôt le formulaire');
        }

        foreach($_aListStations as $_iKey=> &$_aStation)
        {
            $_aStation = $this->getDoctrine()
                ->getRepository('LPDWSncfBundle:Station')
                ->find($_aStation['id']);
        }

        return array(
                'latitude' => $_mLatitude,
                'longitude' => $_mLongitude,
                'liste_stations' => $_aListStations
        );
    }

}
