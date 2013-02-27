<?php

namespace LPDW\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use LPDW\Bundle\ClientBundle\Form\SearchStationType;


class FormController extends Controller
{

    /**
     * @Route ("/formulaire", name="lpdw_formulaire", options={"expose"=true})
     * @Template ("LPDWClientBundle:Client:formulaire.html.twig")
     */
    public function formnulaireAction(Request $_oRequest)
    {
        $_sError = $_oRequest->request->get('errorTxt');


        return array( 'errorTxt' => $_sError);
    }


    /**
     * Fonction qui requête dans la base les gares correspondantes à sa recherche
     *
     * @Route ("/formulaire", name="lpdw_formulaire", options={"expose"=true})
     * @Template ("LPDWClientBundle:Client:formulaire.html.twig")
     */
    public function positionFormulaireAction(Request $_oRequest)
    {

    }


}
