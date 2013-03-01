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
     * @Route("/", name="lpdw_search")
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
                // récupère la valeur du champ 'nom de la gare' du formulaire
                $_sNameStation = $_oForm['name']->getData();

                // appel du service du manager de l'entité Station
                $_aListStations = $this->get('lpdw_sncf.station_manager')->findStationsByName($_sNameStation);


                // Si trop de résultats trouvés, demande à l'utilisateur d'affiner sa recherche
                if ($_aListStations ===  false)
                {
                    $_sErrorTxt = 'Trop de résultats ont été trouvés, veuillez affiner votre recherche';
                    return array(
                        'errorTxt' => $_sErrorTxt,
                        'form' => $_oForm->createView(),
                    );
                }

                // Si pas de gares correspondantes trouvé
                if (!$_aListStations)
                {
                    $_sErrorTxt = 'Aucune gare correspondante à votre recherche n\'a été trouvée';
                    return array(
                        'errorTxt' => $_sErrorTxt,
                        'form' => $_oForm->createView(),
                    );
                }
                return array(
                    'stations' => $_aListStations,
                    'form' => $_oForm->createView(),
                );
            }
        }

        // Rend le formulaire dans la vie s'il n'a pas été soumis
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

        // Si aucune stations n' été trouvée, retourne une erreur
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
