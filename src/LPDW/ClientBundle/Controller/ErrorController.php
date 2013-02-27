<?php

namespace LPDW\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ErrorController extends Controller
{

    /**
     * Renvoie des erreurs si la géocalisation est invalide
     *
     * @Route ("/erreur", name="lpdw_error", options={"expose"=true})
     * @Template ("LPDWClientBundle:Client:error.html.twig")
     */
    public function errorAction(Request $_oRequest)
    {
        $_sError = $_oRequest->request->get('errorTxt');
        return array( 'errorTxt' => $_sError);
    }


}
