<?php

namespace LPDW\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;



class FormController extends Controller
{

    /**
     * @Route ("/formulaire", name="lpdw_formulaire", options={"expose"=true})
     * @Template ("LPDWClientBundle:Client:formulaire.html.twig")
     */
    public function formnulaireAction(Request $_oRequest)
    {
        $_sError = $_oRequest->request->get('errorTxt');

        // fait appel au form builder pour rendre le formulaire
        $_oForm = $this->createFormBuilder()
            ->add('localisation', 'text')
            ->getForm();

        return array( 'errorTxt' => $_sError, 'form' => $_oForm->createView() );
    }


}
